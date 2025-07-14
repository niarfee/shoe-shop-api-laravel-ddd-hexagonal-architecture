<?php

declare(strict_types=1);

namespace Tests\Src\Shop\ProductCategory\Infrastructure\Repository;

use PHPUnit\Framework\Attributes\Group;
use Src\Shop\ProductCategory\Domain\Exception\NoProductCategoriesExistException;
use Src\Shop\ProductCategory\Infrastructure\Mapper\ProductCategoryMapper;
use Src\Shop\ProductCategory\Infrastructure\Repository\EloquentProductCategoryRepository;
use Tests\Utils\IntegrationDatabaseTransactionsLaravelTestCase;
use Tests\Utils\Scenarios\ProductCategoryScenarioBuilder;

final class EloquentProductCategoryRepositoryTest extends IntegrationDatabaseTransactionsLaravelTestCase
{
    private EloquentProductCategoryRepository $repository;

    #[Group('integration')]
    public function test_it_should_return_all_product_categories(): void
    {
        ProductCategoryScenarioBuilder::productCategoryTruncateTable();
        $productCategory1 = ProductCategoryScenarioBuilder::productCategoryMotherMakeAndPersist();
        $productCategory2 = ProductCategoryScenarioBuilder::productCategoryMotherMakeAndPersist();

        $categoriesPersisted = $this->repository->findAll();

        $productCategoryIds = [
            $productCategory1->id()->value(),
            $productCategory2->id()->value(),
        ];
        $this->assertContains($productCategory1->id()->value(), $productCategoryIds);
        $this->assertContains($productCategory2->id()->value(), $productCategoryIds);
        $this->assertCount(2, $categoriesPersisted);
    }

    #[Group('integration')]
    public function test_it_should_throw_exception_if_no_categories_exist(): void
    {
        ProductCategoryScenarioBuilder::productCategoryTruncateTable();
        $this->expectException(NoProductCategoriesExistException::class);

        $this->repository->findAll();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new EloquentProductCategoryRepository(new ProductCategoryMapper());
    }
}
