<?php

declare(strict_types=1);

namespace Tests\App\Shop;

use PHPUnit\Framework\Attributes\Group;
use Tests\Utils\FeatureDatabaseTransactionsLaravelTestCase;
use Tests\Utils\Scenarios\ProductCategoryScenarioBuilder;
use Tests\Utils\Scenarios\ProductScenarioBuilder;

class GetProductControllerTest extends FeatureDatabaseTransactionsLaravelTestCase
{
    #[Group('feature')]
    public function test_it_returns_a_product(): void
    {
        // GIVEN
        $product = ProductScenarioBuilder::productMotherMakeAndPersistWithDependenciesWithNVariants(
            productCategoryId: ProductCategoryScenarioBuilder::productCategoryMotherMakeAndPersist()->id(),
            numberOfVariants: 2,
        );

        // WHEN
        $response = $this->getJson($this->getEndpoint($product->id()->value()));

        // THEN
        $response->assertOk();
        $response->assertJsonFragment([
            'status' => 'success',
            'message' => 'Product successfully retrieved.',
        ]);
        $response->assertJsonStructure([
            'status',
            'message',
            'http' => [
                'code',
                'label',
            ],
            'data' => [
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
        ]);
        $this->assertNotEmpty($response->json('data.variants'));
    }

    #[Group('feature')]
    public function test_it_returns_not_found_for_non_existent_product(): void
    {
        // GIVEN
        $nonExistentProductId = self::ID_NOT_EXISTING;

        // WHEN
        $response = $this->getJson($this->getEndpoint($nonExistentProductId));

        // THEN
        $response->assertNotFound();
        $response->assertJson([
            'status' => 'error',
            'message' => "The product <$nonExistentProductId> has not been found.",
        ]);
    }

    private function getEndpoint(string $productId): string
    {
        return sprintf('/api/v1/products/%s', $productId);
    }
}
