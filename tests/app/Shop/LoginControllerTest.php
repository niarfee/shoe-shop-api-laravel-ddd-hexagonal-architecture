<?php

declare(strict_types=1);

namespace Tests\App\Shop;

use PHPUnit\Framework\Attributes\Group;
use Tests\Src\Shop\User\Domain\ValueObject\UserPasswordMother;
use Tests\Utils\FeatureDatabaseTransactionsLaravelTestCase;
use Tests\Utils\Scenarios\UserScenarioBuilder;

class LoginControllerTest extends FeatureDatabaseTransactionsLaravelTestCase
{
    private const ENDPOINT = '/api/v1/auth/login';

    #[Group('feature')]
    public function test_it_logs_in_successfully(): void
    {
        // GIVEN
        $password = '!Password123';
        $user = UserScenarioBuilder::userMotherMakeAndPersist(
            password: UserPasswordMother::make($password),
        );

        // WHEN
        $response = $this->postJson(self::ENDPOINT, [
            'email' => $user->email()->value(),
            'password' => $password,
        ]);

        // THEN
        $response->assertOk();
        $this->assertValidLoggedUserJsonStructure($response);
        $response->assertJson([
            'status' => 'success',
            'message' => "Hi {$user->email()->value()}",
            'data' => [
                'user' => [
                    'email' => $user->email()->value(),
                ],
                'access_token' => $response->json()['data']['access_token'],
                'token_type' => 'Bearer',
            ],
        ]);
    }

    #[Group('feature')]
    public function test_it_fails_to_login_with_invalid_credentials(): void
    {
        // GIVEN
        $user = UserScenarioBuilder::userMotherMakeAndPersist();

        // WHEN
        $response = $this->postJson(self::ENDPOINT, [
            'email' => $user->email()->value(),
            'password' => 'wrong-password',
        ]);

        // THEN
        $response->assertUnauthorized();
        $response->assertJson([
            'status' => 'error',
            'message' => 'Invalid credentials provided. Please check your email and password.',
        ]);
    }

    #[Group('feature')]
    public function test_login_fails_with_unregistered_email(): void
    {
        // GIVEN: An email that does not exist in the database
        $payload = [
            'email' => 'notexist@example.com',
            'password' => 'password123',
        ];

        // WHEN: We try to login with these credentials
        $response = $this->postJson(self::ENDPOINT, $payload);

        // THEN: The login fails and we get an authentication error.
        $response->assertUnauthorized();
        $response->assertJson([
            'status' => 'error',
            'message' => 'Invalid credentials provided. Please check your email and password.',
        ]);
    }

    #[Group('integration')]
    public function test_login_validation_fails_when_required_fields_are_missing(): void
    {
        $response = $this->postJson(self::ENDPOINT, []);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'status',
            'message',
            'errors' => [
                'email',
                'password',
            ],
        ]);
    }

    #[Group('feature')]
    public function test_it_throttles_after_five_failed_attempts(): void
    {
        // GIVEN
        $user = UserScenarioBuilder::userMotherMakeAndPersist();
        $maxAttempts = 5;

        // WHEN - Make max attempts
        for ($i = 1; $i <= $maxAttempts; $i++) {
            $response = $this->postJson(self::ENDPOINT, [
                'email' => $user->email()->value(),
                'password' => 'wrong-password',
            ]);

            $response->assertHeader('X-RateLimit-Limit', $maxAttempts);
            $response->assertHeader('X-RateLimit-Remaining', $maxAttempts - $i);
            $response->assertHeader('X-RateLimit-Reset');
        }

        // THEN - Next attempt should be throttled
        $response = $this->postJson(self::ENDPOINT, [
            'email' => $user->email()->value(),
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(429);
        $response->assertHeader('Retry-After');
        $response->assertHeader('X-RateLimit-Limit', $maxAttempts);
        $response->assertHeader('X-RateLimit-Remaining', 0);
        $response->assertHeader('X-RateLimit-Reset');

        $response->assertJsonStructure([
            'status',
            'message',
            'http' => ['code', 'label'],
            'errors' => [
                'throttle' => [
                    'resource',
                    'retry_after',
                    'max_attempts',
                ],
            ],
        ]);

        $response->assertJson([
            'status' => 'error',
            'http' => [
                'code' => 429,
                'label' => 'Too Many Requests',
            ],
            'errors' => [
                'throttle' => [
                    'resource' => 'login',
                    'max_attempts' => $maxAttempts,
                ],
            ],
        ]);

        // Verificar que el mensaje sigue el formato con flechitas
        $this->assertMatchesRegularExpression(
            '/Too many login attempts. Please try again in <\d+> seconds\./',
            $response->json('message'),
        );
    }

    #[Group('feature')]
    public function test_it_returns_validation_controller_error_with_invalid_email(): void
    {
        // GIVEN - Invalid email format
        $invalidEmail = 3;

        // WHEN
        $response = $this->postJson(self::ENDPOINT, [
            'email' => $invalidEmail,
            'password' => 'any-password',
        ]);

        // THEN
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }
}
