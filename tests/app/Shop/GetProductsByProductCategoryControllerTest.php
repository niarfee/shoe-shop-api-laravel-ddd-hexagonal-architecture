<?php

declare(strict_types=1);

namespace Tests\App\Shop;

use PHPUnit\Framework\Attributes\Group;
use Tests\Utils\FeatureDatabaseTransactionsLaravelTestCase;
use Tests\Utils\Scenarios\ProductCategoryScenarioBuilder;
use Tests\Utils\Scenarios\ProductScenarioBuilder;

class GetProductsByProductCategoryControllerTest extends FeatureDatabaseTransactionsLaravelTestCase
{
    #[Group('feature')]
    public function test_it_returns_products_by_category(): void
    {
        // GIVEN
        $productCategory = ProductCategoryScenarioBuilder::productCategoryMotherMakeAndPersist();
        ProductScenarioBuilder::productMotherMakeAndPersistWithDependenciesWithNVariants(
            productCategoryId: $productCategory->id(),
            numberOfVariants: 4,
        );
        ProductScenarioBuilder::productMotherMakeAndPersistWithDependenciesWithNVariants(
            productCategoryId: $productCategory->id(),
            numberOfVariants: 7,
        );

        // WHEN
        $response = $this->getJson($this->getEndpoint($productCategory->id()->value()));

        // THEN
        $response->assertOk();
        $response->assertJsonFragment([
            'status' => 'success',
            'message' => 'Products successfully retrieved.',
        ]);
        $response->assertJsonStructure([
            'status',
            'message',
            'http' => [
                'code',
                'label',
            ],
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'price',
                    'price_with_symbol',
                    'variants' => [
                        '*' => [
                            'id',
                            'size',
                            'color',
                            'stock',
                        ],
                    ],
                ],
            ],
        ]);
    }

    #[Group('feature')]
    public function test_it_returns_empty_list_for_category_with_no_products(): void
    {
        // GIVEN
        $productCategory = ProductCategoryScenarioBuilder::productCategoryMotherMakeAndPersist();

        // WHEN
        $response = $this->getJson($this->getEndpoint($productCategory->id()->value()));

        // THEN
        $response->assertOk();
        $response->assertJson([
            'status' => 'success',
            'message' => 'Products successfully retrieved.',
            'data' => [],
        ]);
    }

    #[Group('feature')]
    public function test_it_returns_empty_list_for_non_existent_category(): void
    {
        // GIVEN
        $nonExistentCategoryId = self::ID_NOT_EXISTING;

        // WHEN
        $response = $this->getJson($this->getEndpoint($nonExistentCategoryId));

        // THEN
        $response->assertNotFound();
        $response->assertJson([
            'status' => 'error',
            'message' => "Product category <$nonExistentCategoryId> not found.",
        ]);
    }

    private function getEndpoint(string $categoryId): string
    {
        return sprintf('/api/v1/categories/%s/products', $categoryId);
    }
}
