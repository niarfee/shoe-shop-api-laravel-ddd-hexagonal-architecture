<?php

declare(strict_types=1);

namespace Tests\App\Shop;

use PHPUnit\Framework\Attributes\Group;
use Tests\Utils\FeatureDatabaseTransactionsLaravelTestCase;
use Tests\Utils\Scenarios\LoginScenarioBuilder;

class LogoutControllerTest extends FeatureDatabaseTransactionsLaravelTestCase
{
    private const ENDPOINT = '/api/v1/auth/logout';

    #[Group('feature')]
    public function test_it_logs_out_an_authenticated_user(): void
    {
        // GIVEN
        LoginScenarioBuilder::customerMotherAndUserMotherMakeAndPersistAndLoginSanctum();

        // WHEN
        $response = $this->postJson(self::ENDPOINT);

        // THEN
        $response->assertOk();
        $response->assertJson([
            'status' => 'success',
            'message' => 'You have successfully logged out and the token was successfully deleted',
            'data' => [],
        ]);
    }

    #[Group('feature')]
    public function test_it_requires_authentication(): void
    {
        // WHEN
        $response = $this->postJson(self::ENDPOINT);

        // THEN
        $this->assertNotAuthenticatedStatusAndAssertJson($response);
    }
}
