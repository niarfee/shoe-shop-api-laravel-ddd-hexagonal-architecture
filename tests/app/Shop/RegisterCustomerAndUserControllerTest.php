<?php

declare(strict_types=1);

namespace Tests\App\Shop;

use PHPUnit\Framework\Attributes\Group;
use Tests\Utils\FeatureDatabaseTransactionsLaravelTestCase;

class RegisterCustomerAndUserControllerTest extends FeatureDatabaseTransactionsLaravelTestCase
{
    private const ENDPOINT = '/api/v1/auth/register';

    #[Group('feature')]
    public function test_it_registers_a_new_customer_successfully(): void
    {
        // Given
        $payload = [
            'first_name' => 'Lorem',
            'last_name' => 'Ipsum',
            'email' => 'lorem@ipsum.com',
            'password' => 'My-p4ssw0rd',
            'password_confirm' => 'My-p4ssw0rd',
        ];

        // When
        $response = $this->postJson(self::ENDPOINT, $payload);

        // Then
        $response->assertCreated();
        $this->assertValidLoggedUserJsonStructure($response);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Customer successfully registered.',
            'data' => [
                'user' => [
                    'email' => $payload['email'],
                ],
                'access_token' => $response->json()['data']['access_token'],
                'token_type' => 'Bearer',
            ],
        ]);
    }

    #[Group('feature')]
    public function test_it_fails_when_passwords_do_not_match(): void
    {
        // Given
        $payload = [
            'first_name' => 'Dolor',
            'last_name' => 'Sit',
            'email' => 'dolor@sit.com',
            'password' => 'Password?123',
            'password_confirm' => 'WrongPassword',
        ];

        // When
        $response = $this->postJson(self::ENDPOINT, $payload);

        // Then
        $response->assertUnprocessable();
        $response->assertJsonFragment([
            'status' => 'error',
            'message' => 'Password confirmation does not match.',
        ]);
    }

    #[Group('feature')]
    public function test_it_fails_when_email_already_exists(): void
    {
        // Given
        $sameEmail = 'consectetur@adipiscing.com';
        $this->postJson(self::ENDPOINT, [
            'first_name' => 'consectetur',
            'last_name' => 'adipiscing',
            'email' => $sameEmail,
            'password' => 'Password?123',
            'password_confirm' => 'Password?123',
        ]);

        $payload = [
            'first_name' => 'eiusmod',
            'last_name' => 'tempor',
            'email' => $sameEmail,
            'password' => '456-Password',
            'password_confirm' => '456-Password',
        ];

        // When
        $response = $this->postJson(self::ENDPOINT, $payload);

        // Then
        $response->assertConflict();
        $response->assertJsonFragment([
            'status' => 'error',
            'message' => "Customer email <$sameEmail> not available.",
        ]);
    }

    #[Group('integration')]
    public function test_register_customer_validation_fails_when_required_fields_are_missing(): void
    {
        $response = $this->postJson(self::ENDPOINT, []);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'status',
            'message',
            'errors' => [
                'first_name',
                'last_name',
                'email',
                'password',
                'password_confirm',
            ],
        ]);
    }
}
