<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Order\Infrastructure\Repository;

use PHPUnit\Framework\Attributes\Group;
use Src\Shop\Order\Domain\Exception\OrderLineNotFoundException;
use Src\Shop\Order\Infrastructure\Mapper\OrderLineMapper;
use Src\Shop\Order\Infrastructure\Persistence\Eloquent\OrderLineEloquentModel;
use Src\Shop\Order\Infrastructure\Repository\EloquentOrderLineRepository;
use Tests\Src\Shop\Order\Domain\OrderLineMother;
use Tests\Src\Shop\Order\Domain\ValueObject\OrderLineIdMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\OrderLineUnitsMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\ProductVariantStockMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\ProductVariantStockRequestedMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\StockRequestMother;
use Tests\Utils\IntegrationDatabaseTransactionsLaravelTestCase;
use Tests\Utils\Scenarios\CustomerScenarioBuilder;
use Tests\Utils\Scenarios\OrderScenarioBuilder;
use Tests\Utils\Scenarios\ProductCategoryScenarioBuilder;
use Tests\Utils\Scenarios\ProductScenarioBuilder;
use Tests\Utils\Scenarios\ProductVariantScenarioBuilder;

final class EloquentOrderLineRepositoryTest extends IntegrationDatabaseTransactionsLaravelTestCase
{
    private EloquentOrderLineRepository $repository;
    private OrderLineMapper $mapper;

    #[Group('integration')]
    public function test_should_create_order_line(): void
    {
        $order = OrderScenarioBuilder::orderMotherMakeAndPersist(
            customerId: CustomerScenarioBuilder::customerMotherMakeAndPersist()->id(),
        );
        $productVariant = ProductVariantScenarioBuilder::productVariantMotherMakeAndPersist(
            productId: ProductScenarioBuilder::productMotherMakeAndPersist(
                productCategoryId: ProductCategoryScenarioBuilder::productCategoryMotherMakeAndPersist()->id(),
            )->id(),
        );

        $line = OrderLineMother::make(
            orderId: $order->id(),
            productVariantId: $productVariant->id(),
        );

        $this->repository->save($line);
        $created = $this->mapper->fromEloquentToDomain(
            OrderLineEloquentModel::find($line->id()->value()),
        );

        $this->assertNotNull($created->id());
        $this->assertTrue($line->orderId()->equals($created->orderId()));
        $this->assertTrue($line->productVariantId()->equals($created->productVariantId()));
        $this->assertEquals($line->units()->value(), $created->units()->value());
    }

    #[Group('integration')]
    public function test_should_update_order_line(): void
    {
        $productVariantStock = ProductVariantStockMother::make(18);
        $order = OrderScenarioBuilder::orderMotherMakeAndPersist(
            customerId: CustomerScenarioBuilder::customerMotherMakeAndPersist()->id(),
        );
        $variant = ProductVariantScenarioBuilder::productVariantMotherMakeAndPersist(
            productId: ProductScenarioBuilder::productMotherMakeAndPersist(
                productCategoryId: ProductCategoryScenarioBuilder::productCategoryMotherMakeAndPersist()->id(),
            )->id(),
            stock: $productVariantStock,
        );

        // Create order line
        $lineToCreate = OrderLineMother::make(
            orderId: $order->id(),
            productVariantId: $variant->id(),
            units: OrderLineUnitsMother::make(
                random_int(1, 10),
            ),
        );
        $this->repository->save($lineToCreate);
        $created = $this->mapper->fromEloquentToDomain(
            OrderLineEloquentModel::find($lineToCreate->id()->value()),
        );

        // Update order line whit stock 5
        $created->updateUnitsBasedOnStock(
            StockRequestMother::make(
                ProductVariantStockRequestedMother::make(5),
                $productVariantStock,
            ),
        );
        $this->repository->save($created);
        $updated = $this->mapper->fromEloquentToDomain(
            OrderLineEloquentModel::find($created->id()->value()),
        );

        $this->assertTrue($created->id()->equals($updated->id()));
        $this->assertTrue($created->orderId()->equals($updated->orderId()));
        $this->assertTrue($created->productVariantId()->equals($updated->productVariantId()));
        $this->assertEquals(5, $updated->units()->value());
    }

    #[Group('integration')]
    public function test_should_delete_order_line(): void
    {
        $order = OrderScenarioBuilder::orderMotherMakeAndPersist(
            customerId: CustomerScenarioBuilder::customerMotherMakeAndPersist()->id(),
        );
        $variant = ProductVariantScenarioBuilder::productVariantMotherMakeAndPersist(
            productId: ProductScenarioBuilder::productMotherMakeAndPersist(
                productCategoryId: ProductCategoryScenarioBuilder::productCategoryMotherMakeAndPersist()->id(),
            )->id(),
        );

        $line = OrderLineMother::make(
            orderId: $order->id(),
            productVariantId: $variant->id(),
        );

        $this->repository->save($line);
        $created = $this->mapper->fromEloquentToDomain(
            OrderLineEloquentModel::find($line->id()->value()),
        );

        $this->repository->delete($created->id());

        $this->assertNull(OrderLineEloquentModel::find($created->id()->value()));
    }

    #[Group('integration')]
    public function test_it_throws_exception_when_deleting_nonexistent_order_line(): void
    {
        $this->expectException(OrderLineNotFoundException::class);

        $this->repository->delete(
            OrderLineIdMother::make(self::ID_NOT_EXISTING),
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->mapper = new OrderLineMapper();
        $this->repository = new EloquentOrderLineRepository(
            $this->mapper,
        );
    }
}
