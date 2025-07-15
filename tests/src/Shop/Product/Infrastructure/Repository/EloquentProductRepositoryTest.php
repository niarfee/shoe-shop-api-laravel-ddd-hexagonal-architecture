<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Product\Infrastructure\Repository;

use PHPUnit\Framework\Attributes\Group;
use Src\Shop\Product\Domain\Exception\ProductNotFoundException;
use Src\Shop\Product\Domain\ValueObject\ProductVariantColor;
use Src\Shop\Product\Infrastructure\Mapper\ProductMapper;
use Src\Shop\Product\Infrastructure\Mapper\ProductVariantMapper;
use Src\Shop\Product\Infrastructure\Repository\EloquentProductRepository;
use Src\Shop\ProductCategory\Domain\Exception\ProductCategoryNotFoundException;
use Tests\Src\Shop\Product\Domain\ValueObject\ProductIdMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\ProductCategoryIdMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\ProductVariantIdMother;
use Tests\Utils\IntegrationDatabaseTransactionsLaravelTestCase;
use Tests\Utils\Scenarios\ProductCategoryScenarioBuilder;
use Tests\Utils\Scenarios\ProductScenarioBuilder;
use Tests\Utils\Scenarios\ProductVariantScenarioBuilder;

final class EloquentProductRepositoryTest extends IntegrationDatabaseTransactionsLaravelTestCase
{
    private EloquentProductRepository $repository;

    #[Group('integration')]
    public function test_it_should_search_products_by_category(): void
    {
        $productCategory = ProductCategoryScenarioBuilder::productCategoryMotherMakeAndPersist();

        $product1 = ProductScenarioBuilder::productMotherMakeAndPersist(
            productCategoryId: $productCategory->id(),
        );
        $productVariant1 = ProductVariantScenarioBuilder::productVariantMotherMakeAndPersist(productId: $product1->id());
        $product1->addVariant($productVariant1);

        $product2 = ProductScenarioBuilder::productMotherMakeAndPersist(
            productCategoryId: $productCategory->id(),
        );
        // We assign different colors so that it does not generate variant combinations with the same color and size (it would throw exception).
        $productVariant2 = ProductVariantScenarioBuilder::productVariantMotherMakeAndPersist(
            productId: $product2->id(),
            color: ProductVariantColor::createRed(),
        );
        $productVariant3 = ProductVariantScenarioBuilder::productVariantMotherMakeAndPersist(
            productId: $product2->id(),
            color: ProductVariantColor::createBlack(),
        );
        $productVariant4 = ProductVariantScenarioBuilder::productVariantMotherMakeAndPersist(
            productId: $product2->id(),
            color: ProductVariantColor::createGreen(),
        );
        $product2->addVariants([$productVariant2, $productVariant3, $productVariant4]);

        $productsPersisted = $this->repository->searchByProductCategoryId($productCategory->id());

        $productPersisted1 = current(array_filter($productsPersisted, fn ($product) => $product->id()->value() === $product1->id()->value()));
        $productPersisted2 = current(array_filter($productsPersisted, fn ($product) => $product->id()->value() === $product2->id()->value()));
        $this->assertCount(2, $productsPersisted);
        $this->assertCount(1, $productPersisted1->variants());
        $this->assertCount(3, $productPersisted2->variants());
    }

    #[Group('integration')]
    public function test_it_should_throw_exception_if_category_does_not_exist(): void
    {
        $this->expectException(ProductCategoryNotFoundException::class);

        $this->repository->searchByProductCategoryId(
            ProductCategoryIdMother::make(self::ID_NOT_EXISTING),
        );
    }

    #[Group('integration')]
    public function test_it_should_find_a_product_by_id(): void
    {
        $product = ProductScenarioBuilder::productMotherMakeAndPersist(
            productCategoryId: ProductCategoryScenarioBuilder::productCategoryMotherMakeAndPersist()->id(),
        );
        // We assign different colors so that it does not generate variant combinations with the same color and size (it would throw exception).
        $productVariant1 = ProductVariantScenarioBuilder::productVariantMotherMakeAndPersist(
            productId: $product->id(),
            color: ProductVariantColor::createWhite(),
        );
        $productVariant2 = ProductVariantScenarioBuilder::productVariantMotherMakeAndPersist(
            productId: $product->id(),
            color: ProductVariantColor::createOcre(),
        );
        $product->addVariants([$productVariant1, $productVariant2]);

        $productPersisted = $this->repository->findById($product->id());

        $variantIds = array_map(
            fn ($variant) => $variant->id()->value(),
            $productPersisted->variants(),
        );
        $this->assertTrue($product->id()->equals($productPersisted->id()));
        $this->assertCount(2, $productPersisted->variants());
        $this->assertContains($productVariant1->id()->value(), $variantIds);
        $this->assertContains($productVariant2->id()->value(), $variantIds);
    }

    #[Group('integration')]
    public function test_it_should_throw_exception_if_product_does_not_exist(): void
    {
        $this->expectException(ProductNotFoundException::class);

        $this->repository->findById(
            ProductIdMother::make(self::ID_NOT_EXISTING),
        );
    }

    #[Group('integration')]
    public function test_it_should_find_a_product_by_product_variant_id(): void
    {
        $product = ProductScenarioBuilder::productMotherMakeAndPersist(
            productCategoryId: ProductCategoryScenarioBuilder::productCategoryMotherMakeAndPersist()->id(),
        );
        $productVariant1 = ProductVariantScenarioBuilder::productVariantMotherMakeAndPersist(
            productId: $product->id(),
            color: ProductVariantColor::createBlue(),
        );
        $productVariant2 = ProductVariantScenarioBuilder::productVariantMotherMakeAndPersist(
            productId: $product->id(),
            color: ProductVariantColor::createRed(),
        );
        $product->addVariants([$productVariant1, $productVariant2]);

        $productPersisted = $this->repository->findByProductVariantId($productVariant1->id());

        $variantIds = array_map(
            fn ($variant) => $variant->id()->value(),
            $productPersisted->variants(),
        );
        $this->assertTrue($product->id()->equals($productPersisted->id()));
        $this->assertCount(2, $productPersisted->variants());
        $this->assertContains($productVariant1->id()->value(), $variantIds);
        $this->assertContains($productVariant2->id()->value(), $variantIds);
    }

    #[Group('integration')]
    public function it_should_throw_exception_if_product_variant_id_not_found(): void
    {
        $this->expectException(ProductNotFoundException::class);

        $this->repository->findByProductVariantId(
            ProductVariantIdMother::make(self::ID_NOT_EXISTING),
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new EloquentProductRepository(
            new ProductMapper(
                new ProductVariantMapper(),
            ),
        );
    }
}
