<?php

declare(strict_types=1);

namespace Tests\App\Shop;

use PHPUnit\Framework\Attributes\Group;
use Src\Shop\Order\Domain\ValueObject\OrderStatus;
use Tests\Utils\FeatureDatabaseTransactionsLaravelTestCase;
use Tests\Utils\Scenarios\LoginScenarioBuilder;
use Tests\Utils\Scenarios\OrderScenarioBuilder;

class GetOrdersControllerTest extends FeatureDatabaseTransactionsLaravelTestCase
{
    private const ENDPOINT = '/api/v1/orders';

    #[Group('feature')]
    public function test_it_returns_orders_for_customer_with_orders(): void
    {
        // GIVEN
        $customer = LoginScenarioBuilder::customerMotherAndUserMotherMakeAndPersistAndLoginSanctum()->customer;
        OrderScenarioBuilder::orderMotherMakeAndPersistWithDependenciesWithNLines(
            customerId: $customer->id(),
            status: OrderStatus::pendingShipment(),
            numberOfLines: 1,
        );

        // WHEN
        $response = $this->getJson(self::ENDPOINT);

        // THEN
        $response->assertOk();
        $response->assertJsonFragment([
            'status' => 'success',
            'message' => 'Orders successfully retrieved.',
        ]);
        $this->assertValidOrdersListJsonStructure($response);
    }

    #[Group('feature')]
    public function test_it_returns_empty_list_for_user_without_orders(): void
    {
        // GIVEN
        LoginScenarioBuilder::customerMotherAndUserMotherMakeAndPersistAndLoginSanctum();

        // WHEN
        $response = $this->getJson(self::ENDPOINT);

        // THEN
        $response->assertOk();
        $response->assertJson([
            'status' => 'success',
            'message' => 'Orders successfully retrieved.',
            'data' => [],
        ]);
    }

    #[Group('feature')]
    public function test_it_returns_unauthorized_if_user_is_not_authenticated(): void
    {
        // GIVEN

        // WHEN
        $response = $this->getJson(self::ENDPOINT);

        // THEN
        $this->assertNotAuthenticatedStatusAndAssertJson($response);
    }
}
