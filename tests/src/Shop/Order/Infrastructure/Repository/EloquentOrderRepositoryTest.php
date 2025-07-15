<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Order\Infrastructure\Repository;

use PHPUnit\Framework\Attributes\Group;
use Src\Shop\Order\Domain\Order;
use Src\Shop\Order\Domain\ValueObject\OrderStatus;
use Src\Shop\Order\Domain\ValueObject\OrderTotalPrice;
use Src\Shop\Order\Infrastructure\Mapper\OrderLineMapper;
use Src\Shop\Order\Infrastructure\Mapper\OrderMapper;
use Src\Shop\Order\Infrastructure\Persistence\Eloquent\OrderEloquentModel;
use Src\Shop\Order\Infrastructure\Repository\EloquentOrderRepository;
use Tests\Src\Shop\Order\Domain\OrderMother;
use Tests\Utils\IntegrationDatabaseTransactionsLaravelTestCase;
use Tests\Utils\Scenarios\CustomerScenarioBuilder;
use Tests\Utils\Scenarios\LoginScenarioBuilder;
use Tests\Utils\Scenarios\OrderScenarioBuilder;

final class EloquentOrderRepositoryTest extends IntegrationDatabaseTransactionsLaravelTestCase
{
    private EloquentOrderRepository $repository;
    private OrderMapper $mapper;

    #[Group('integration')]
    public function test_it_should_return_order_cart_if_customer_has_a_cart(): void
    {
        $customerWithCart = CustomerScenarioBuilder::customerMotherMakeAndPersist();
        $orderCartToCreate = Order::createEmptyOrderCart($customerWithCart->id());
        $this->repository->save($orderCartToCreate);

        $created = $this->repository->searchCartByCustomerId($customerWithCart->id());

        $this->assertInstanceOf(Order::class, $created);
        $this->assertTrue($orderCartToCreate->id()->equals($created->id()));
        $this->assertEquals(OrderStatus::inCart(), $created->status());
    }

    #[Group('integration')]
    public function test_it_should_return_null_if_customer_does_not_have_a_cart(): void
    {
        $customerWithOrderCompleted = CustomerScenarioBuilder::customerMotherMakeAndPersist();
        $orderCompletedToCreate = OrderMother::make(
            customerId: $customerWithOrderCompleted->id(),
            status: OrderStatus::completed(),
        );
        $this->repository->save($orderCompletedToCreate);

        $this->assertNull(
            $this->repository->searchCartByCustomerId($customerWithOrderCompleted->id()),
        );
    }

    #[Group('integration')]
    public function test_it_should_return_null_if_customer_has_no_orders_and_no_cart(): void
    {
        $customerWithoutCart = CustomerScenarioBuilder::customerMotherMakeAndPersist();

        $this->assertNull(
            $this->repository->searchCartByCustomerId($customerWithoutCart->id()),
        );
    }

    #[Group('integration')]
    public function test_should_persist_new_order_cart(): void
    {
        $customer = LoginScenarioBuilder::customerMotherAndUserMotherMakeAndPersistAndLoginAuth()->customer;
        $orderCartToSave = Order::createEmptyOrderCart($customer->id());

        $this->repository->save($orderCartToSave);

        $created = $this->repository->searchCartByCustomerId($customer->id());
        $this->assertTrue($orderCartToSave->id()->equals($created->id()));
        $this->assertEquals(OrderStatus::inCart(), $created->status());
        $this->assertTrue($customer->id()->equals($created->customerId()));
    }

    #[Group('integration')]
    public function test_should_retrieve_existing_cart(): void
    {
        $customer = LoginScenarioBuilder::customerMotherAndUserMotherMakeAndPersistAndLoginAuth()->customer;
        $orderCartToSave = Order::createEmptyOrderCart($customer->id());
        $this->repository->save($orderCartToSave);

        $created = $this->repository->searchCartByCustomerId($customer->id());

        $this->assertNotNull($created->id());
        $this->assertTrue($orderCartToSave->id()->equals($created->id()));
        $this->assertEquals(OrderStatus::inCart(), $created->status());
        $this->assertTrue($customer->id()->equals($created->customerId()));
    }

    #[Group('integration')]
    public function test_should_update_order_fields(): void
    {
        $customer = LoginScenarioBuilder::customerMotherAndUserMotherMakeAndPersistAndLoginAuth()->customer;
        $orderCartToCreate = Order::createEmptyOrderCart($customer->id());
        $this->repository->save($orderCartToCreate);
        $orderCartToUpdate = OrderMother::make(
            id: $orderCartToCreate->id(),
            customerId: $orderCartToCreate->customerId(),
            status: OrderStatus::pendingShipment(),
            totalPrice: new OrderTotalPrice(199.99),
            lines: $orderCartToCreate->lines(),
        );

        $this->repository->save($orderCartToUpdate);

        $updated = $this->mapper->fromEloquentToDomain(
            OrderEloquentModel::find($orderCartToUpdate->id()->value()),
        );
        $this->assertTrue($orderCartToCreate->id()->equals($updated->id()));
        $this->assertEquals(OrderStatus::pendingShipment(), $updated->status());
        $this->assertEquals(199.99, $updated->totalPrice()->value());
    }

    #[Group('integration')]
    public function test_should_return_only_non_cart_orders_from_my_orders(): void
    {
        $customer = LoginScenarioBuilder::customerMotherAndUserMotherMakeAndPersistAndLoginAuth()->customer;
        // Creates two orders with status != in_cart
        $order1 = OrderScenarioBuilder::orderMotherMakeAndPersist(
            customerId: $customer->id(),
            status: OrderStatus::completed(),
        );
        $order2 = OrderScenarioBuilder::orderMotherMakeAndPersist(
            customerId: $customer->id(),
            status: OrderStatus::sent(),
        );

        $ordersPersisted = $this->repository->searchOrdersByCustomerId($customer->id());

        $this->assertCount(2, $ordersPersisted);
        $orderPersisted1 = current(array_filter($ordersPersisted, fn ($order) => $order->id()->value() === $order1->id()->value()));
        $orderPersisted2 = current(array_filter($ordersPersisted, fn ($order) => $order->id()->value() === $order2->id()->value()));
        $this->assertNotFalse($orderPersisted1);
        $this->assertNotFalse($orderPersisted2);
        $this->assertEquals(OrderStatus::completed(), $orderPersisted1->status());
        $this->assertEquals(OrderStatus::sent(), $orderPersisted2->status());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->mapper = new OrderMapper(
            new OrderLineMapper(),
        );
        $this->repository = new EloquentOrderRepository(
            $this->mapper,
        );
    }
}
