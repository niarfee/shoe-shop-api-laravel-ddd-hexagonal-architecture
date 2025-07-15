<?php

declare(strict_types=1);

namespace Tests\App\Shop;

use PHPUnit\Framework\Attributes\Group;
use Src\Shop\Order\Domain\ValueObject\OrderStatus;
use Tests\Utils\FeatureDatabaseTransactionsLaravelTestCase;
use Tests\Utils\Scenarios\LoginScenarioBuilder;
use Tests\Utils\Scenarios\OrderScenarioBuilder;

class ConfirmCartControllerTest extends FeatureDatabaseTransactionsLaravelTestCase
{
    private const ENDPOINT = '/api/v1/cart/confirm';

    #[Group('feature')]
    public function test_it_confirms_cart_order_and_returns_expected_response()
    {
        // GIVEN
        $customer = LoginScenarioBuilder::customerMotherAndUserMotherMakeAndPersistAndLoginSanctum()->customer;
        OrderScenarioBuilder::orderMotherMakeAndPersistWithDependenciesWithNLines(
            customerId: $customer->id(),
            status: OrderStatus::inCart(),
            numberOfLines: 1,
        );

        // WHEN
        $response = $this->postJson(self::ENDPOINT);

        // THEN
        $response->assertOk();
        $this->assertValidOrderWithOneLineJsonStructure($response);
        $response->assertJsonFragment([
            'status' => 'success',
            'message' => 'Order successfully confirmed.',
        ]);
    }

    #[Group('feature')]
    public function test_it_returns_error_if_cart_not_found()
    {
        // GIVEN
        $customer = LoginScenarioBuilder::customerMotherAndUserMotherMakeAndPersistAndLoginSanctum()->customer;
        $customerIdValue = $customer->id()->value();

        // WHEN
        $response = $this->postJson(self::ENDPOINT);

        // THEN
        $response->assertNotFound();
        $response->assertJsonFragment([
            'status' => 'error',
            'message' => "Cart not found for customer <$customerIdValue>.",
        ]);
    }

    #[Group('feature')]
    public function test_it_returns_error_if_cart_has_no_lines()
    {
        // GIVEN
        $customer = LoginScenarioBuilder::customerMotherAndUserMotherMakeAndPersistAndLoginSanctum()->customer;
        $orderCart = OrderScenarioBuilder::orderMotherMakeAndPersistWithDependenciesWithNLines(
            customerId: $customer->id(),
            status: OrderStatus::inCart(),
            numberOfLines: 0,
        );
        $orderCartIdValue = $orderCart->id()->value();

        // WHEN
        $response = $this->postJson(self::ENDPOINT);

        // THEN
        $response->assertNotFound();
        $response->assertJsonFragment([
            'status' => 'error',
            'message' => "Cart <$orderCartIdValue> is empty.",
        ]);
    }

    #[Group('feature')]
    public function test_it_returns_unauthorized_if_user_is_not_authenticated()
    {
        // GIVEN

        // WHEN
        $response = $this->postJson(self::ENDPOINT);

        // THEN
        $this->assertNotAuthenticatedStatusAndAssertJson($response);
    }

    #[Group('feature')]
    public function test_it_cannot_confirm_an_already_confirmed_cart()
    {

        // GIVEN
        $customer = LoginScenarioBuilder::customerMotherAndUserMotherMakeAndPersistAndLoginSanctum()->customer;
        $customerIdValue = $customer->id()->value();
        OrderScenarioBuilder::orderMotherMakeAndPersistWithDependenciesWithNLines(
            customerId: $customer->id(),
            status: OrderStatus::inCart(),
            numberOfLines: 1,
        );
        // One cart already confirmed
        $responseConfirmFirstTime = $this->postJson(self::ENDPOINT);
        $responseConfirmFirstTime->assertOk();

        // WHEN
        // I try to confirm again
        $responseConfirmSecondTime = $this->postJson(self::ENDPOINT);

        // THEN
        // Should fail because there is no more cart
        $responseConfirmSecondTime->assertNotFound();
        $responseConfirmSecondTime->assertJsonFragment([
            'status' => 'error',
            'message' => "Cart not found for customer <$customerIdValue>.",
        ]);
    }
}
