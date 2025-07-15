<?php

declare(strict_types=1);

namespace Tests\App\Shop;

use PHPUnit\Framework\Attributes\Group;
use Src\Shop\Order\Domain\ValueObject\OrderStatus;
use Tests\Utils\FeatureDatabaseTransactionsLaravelTestCase;
use Tests\Utils\Scenarios\LoginScenarioBuilder;
use Tests\Utils\Scenarios\OrderScenarioBuilder;

class GetCartControllerTest extends FeatureDatabaseTransactionsLaravelTestCase
{
    private const ENDPOINT = '/api/v1/cart';

    #[Group('feature')]
    public function test_it_returns_cart_if_exists(): void
    {
        // GIVEN
        $customer = LoginScenarioBuilder::customerMotherAndUserMotherMakeAndPersistAndLoginSanctum()->customer;
        OrderScenarioBuilder::orderMotherMakeAndPersistWithDependenciesWithNLines(
            customerId: $customer->id(),
            status: OrderStatus::inCart(),
            numberOfLines: 1,
        );

        // WHEN
        $response = $this->getJson(self::ENDPOINT);

        // THEN
        $response->assertOk();
        $this->assertValidOrderWithOneLineJsonStructure($response);
        $response->assertJsonFragment([
            'status' => 'success',
            'message' => 'Cart successfully retrieved.',
        ]);
    }

    #[Group('feature')]
    public function test_it_returns_empty_cart_if_user_has_no_cart_yet(): void
    {
        // GIVEN
        $customer = LoginScenarioBuilder::customerMotherAndUserMotherMakeAndPersistAndLoginSanctum()->customer;

        // WHEN
        $response = $this->getJson(self::ENDPOINT);

        // THEN
        $response->assertOk();
        $response->assertJson([
            'status' => 'success',
            'message' => 'Cart successfully retrieved.',
            'data' => [
                'id' => $response->json()['data']['id'],
                'customer_id' => $customer->id()->value(),
                'status' => [
                    'value' => OrderStatus::IN_CART,
                    'label' => OrderStatus::inCartLabel(),
                ],
                'total_price' => 0,
                'lines' => [],
            ],
        ]);
        $this->assertValidOrderWithoutLinesJsonStructure($response);
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
