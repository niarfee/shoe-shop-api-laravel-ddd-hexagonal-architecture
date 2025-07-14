<?php

declare(strict_types=1);

namespace Tests\App\Shop;

use PHPUnit\Framework\Attributes\Group;
use Tests\Utils\FeatureDatabaseTransactionsLaravelTestCase;
use Tests\Utils\Scenarios\ProductCategoryScenarioBuilder;

class GetAllProductCategoriesControllerTest extends FeatureDatabaseTransactionsLaravelTestCase
{
    private const ENDPOINT = '/api/v1/categories';

    #[Group('feature')]
    public function test_it_returns_all_product_categories(): void
    {
        // GIVEN
        ProductCategoryScenarioBuilder::productCategoryMotherMakeAndPersist();
        ProductCategoryScenarioBuilder::productCategoryMotherMakeAndPersist();

        // WHEN
        $response = $this->getJson(self::ENDPOINT);

        // THEN
        $response->assertOk();
        $response->assertJsonFragment([
            'status' => 'success',
            'message' => 'Product categories successfully retrieved.',
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
                    'slug',
                ],
            ],
        ]);

        $this->assertGreaterThanOrEqual(1, $response->json('data'));
    }

    #[Group('feature')]
    public function test_it_returns_empty_list_when_no_categories_exist(): void
    {
        // GIVEN
        ProductCategoryScenarioBuilder::productCategoryTruncateTable();

        // WHEN
        $response = $this->getJson(self::ENDPOINT);

        // THEN
        $response->assertNotFound();
        $response->assertJson([
            'status' => 'error',
            'message' => 'No categories found.',
        ]);
    }
}
