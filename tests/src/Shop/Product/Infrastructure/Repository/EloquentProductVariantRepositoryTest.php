<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Product\Infrastructure\Repository;

use PHPUnit\Framework\Attributes\Group;
use Src\Shop\Product\Domain\Exception\ProductVariantNotFoundException;
use Src\Shop\Product\Infrastructure\Mapper\ProductVariantMapper;
use Src\Shop\Product\Infrastructure\Persistence\Eloquent\ProductVariantEloquentModel;
use Src\Shop\Product\Infrastructure\Repository\EloquentProductVariantRepository;
use Src\Shop\Shared\Domain\ValueObject\OrderLineUnits;
use Tests\Src\Shop\Product\Domain\ProductVariantMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\ProductVariantIdMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\ProductVariantStockMother;
use Tests\Utils\IntegrationDatabaseTransactionsLaravelTestCase;
use Tests\Utils\Scenarios\ProductCategoryScenarioBuilder;
use Tests\Utils\Scenarios\ProductScenarioBuilder;
use Tests\Utils\Scenarios\ProductVariantScenarioBuilder;

final class EloquentProductVariantRepositoryTest extends IntegrationDatabaseTransactionsLaravelTestCase
{
    private ProductVariantMapper $mapper;
    private EloquentProductVariantRepository $repository;

    #[Group('integration')]
    public function test_it_should_find_a_product_variant(): void
    {
        $productVariant = ProductVariantScenarioBuilder::productVariantMotherMakeAndPersist(
            productId: ProductScenarioBuilder::productMotherMakeAndPersist(
                productCategoryId: ProductCategoryScenarioBuilder::productCategoryMotherMakeAndPersist()->id(),
            )->id(),
        );

        $productVariantPersisted = $this->repository->findById($productVariant->id());

        $this->assertTrue($productVariant->id()->equals($productVariantPersisted->id()));
    }

    #[Group('integration')]
    public function test_it_throws_exception_when_product_variant_not_found(): void
    {
        $this->expectException(ProductVariantNotFoundException::class);

        $this->repository->findById(
            ProductVariantIdMother::make(self::ID_NOT_EXISTING),
        );
    }

    #[Group('integration')]
    public function test_it_should_create_variant(): void
    {
        $productVariantToCreate = ProductVariantMother::make(
            productId: ProductScenarioBuilder::productMotherMakeAndPersist(
                productCategoryId: ProductCategoryScenarioBuilder::productCategoryMotherMakeAndPersist()->id(),
            )->id(),
        );

        $this->repository->save($productVariantToCreate);

        $productVariantCreated = $this->mapper->fromEloquentToDomain(
            ProductVariantEloquentModel::find($productVariantToCreate->id()->value()),
        );
        $this->assertEquals($productVariantToCreate->stock()->value(), $productVariantCreated->stock()->value());
    }

    #[Group('integration')]
    public function test_it_should_update_variant_stock(): void
    {
        $productVariantToUpdate = ProductVariantScenarioBuilder::productVariantMotherMakeAndPersist(
            productId: ProductScenarioBuilder::productMotherMakeAndPersist(
                productCategoryId: ProductCategoryScenarioBuilder::productCategoryMotherMakeAndPersist()->id(),
            )->id(),
            stock: ProductVariantStockMother::make(7),
        );
        $productVariantToUpdate->decrementStock(
            new OrderLineUnits(1),
        );

        $this->repository->save($productVariantToUpdate);

        $productVariantUpdated = $this->mapper->fromEloquentToDomain(
            ProductVariantEloquentModel::find($productVariantToUpdate->id()->value()),
        );
        $this->assertEquals($productVariantToUpdate->stock()->value(), $productVariantUpdated->stock()->value());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->mapper = new ProductVariantMapper();
        $this->repository = new EloquentProductVariantRepository(
            $this->mapper,
        );
    }
}
