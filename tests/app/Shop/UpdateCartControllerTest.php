<?php

declare(strict_types=1);

namespace Tests\App\Shop;

use PHPUnit\Framework\Attributes\Group;
use Tests\Src\Shop\Shared\Domain\ValueObject\ProductVariantStockMother;
use Tests\Utils\FeatureDatabaseTransactionsLaravelTestCase;
use Tests\Utils\Scenarios\LoginScenarioBuilder;
use Tests\Utils\Scenarios\ProductCategoryScenarioBuilder;
use Tests\Utils\Scenarios\ProductScenarioBuilder;
use Tests\Utils\Scenarios\ProductVariantScenarioBuilder;

class UpdateCartControllerTest extends FeatureDatabaseTransactionsLaravelTestCase
{
    private const ENDPOINT = '/api/v1/cart/update';

    #[Group('feature')]
    public function test_it_updates_units_of_product_variant_in_cart(): void
    {
        // GIVEN
        $productVariant = ProductVariantScenarioBuilder::productVariantMotherMakeAndPersist(
            stock: ProductVariantStockMother::make(10),
            productId: ProductScenarioBuilder::productMotherMakeAndPersist(
                productCategoryId: ProductCategoryScenarioBuilder::productCategoryMotherMakeAndPersist()->id(),
            )->id(),
        );
        $payload = [
            'product_variant_id' => $productVariant->id()->value(),
            'units' => 4,
        ];
        LoginScenarioBuilder::customerMotherAndUserMotherMakeAndPersistAndLoginSanctum();

        // WHEN
        $response = $this->putJson(
            self::ENDPOINT,
            $payload,
        );

        // THEN
        $response->assertOk();
        $this->assertValidOrderWithOneLineJsonStructure($response);
        $response->assertJsonFragment([
            'status' => 'success',
            'message' => 'Product updated in cart.',
        ]);
        $responseLine = collect($response->json('data.lines'))->firstWhere('product_variant_id', $productVariant->id()->value());
        $this->assertNotFalse($responseLine);
        $this->assertEquals(4, $responseLine['units']);
    }

    #[Group('feature')]
    public function test_it_updates_cart_with_multiple_lines(): void
    {
        // GIVEN
        $productVariant1 = ProductVariantScenarioBuilder::productVariantMotherMakeAndPersist(
            stock: ProductVariantStockMother::make(27),
            productId: ProductScenarioBuilder::productMotherMakeAndPersist(
                productCategoryId: ProductCategoryScenarioBuilder::productCategoryMotherMakeAndPersist()->id(),
            )->id(),
        );
        $productVariant2 = ProductVariantScenarioBuilder::productVariantMotherMakeAndPersist(
            stock: ProductVariantStockMother::make(31),
            productId: ProductScenarioBuilder::productMotherMakeAndPersist(
                productCategoryId: ProductCategoryScenarioBuilder::productCategoryMotherMakeAndPersist()->id(),
            )->id(),
        );

        LoginScenarioBuilder::customerMotherAndUserMotherMakeAndPersistAndLoginSanctum();
        $this->putJson(self::ENDPOINT, [
            'product_variant_id' => $productVariant1->id()->value(),
            'units' => 3,
        ]);

        // WHEN
        $response = $this->putJson(self::ENDPOINT, [
            'product_variant_id' => $productVariant2->id()->value(),
            'units' => 2,
        ]);

        // THEN
        $response->assertOk();
        $this->assertValidOrderWithTwoLineJsonStructure($response);

        $responseLine1 = collect($response->json('data.lines'))->firstWhere('product_variant_id', $productVariant1->id()->value());
        $this->assertNotFalse($responseLine1);
        $this->assertEquals(3, $responseLine1['units']);

        $responseLine2 = collect($response->json('data.lines'))->firstWhere('product_variant_id', $productVariant2->id()->value());
        $this->assertNotFalse($responseLine2);
        $this->assertEquals(2, $responseLine2['units']);
    }

    #[Group('feature')]
    public function test_it_removes_order_line_when_units_is_zero(): void
    {
        // GIVEN
        $productVariant = ProductVariantScenarioBuilder::productVariantMotherMakeAndPersist(
            stock: ProductVariantStockMother::make(41),
            productId: ProductScenarioBuilder::productMotherMakeAndPersist(
                productCategoryId: ProductCategoryScenarioBuilder::productCategoryMotherMakeAndPersist()->id(),
            )->id(),
        );
        LoginScenarioBuilder::customerMotherAndUserMotherMakeAndPersistAndLoginSanctum();
        // First, we add a product to the cart to ensure it exists
        $this->putJson(self::ENDPOINT, [
            'product_variant_id' => $productVariant->id()->value(),
            'units' => 10,
        ]);
        $payload = [
            'product_variant_id' => $productVariant->id()->value(),
            'units' => 0,
        ];

        // WHEN
        // Now we attempt to remove the product by setting units to zero
        $response = $this->putJson(self::ENDPOINT, $payload);

        // THEN
        $response->assertOk();
        $this->assertValidOrderWithoutLinesJsonStructure($response);
        $response->assertJsonFragment([
            'status' => 'success',
            'message' => 'Product updated in cart.',
        ]);
        $this->assertFalse(
            collect($response->json('data.lines'))->contains('product_variant_id', $productVariant->id()->value()),
        );
    }

    /**
     * This test validates that the following OrderLine unique index exists and does not give problems with the use case logic:
     * $table->unique([“order_id”, ‘product_variant_id’, “deleted_at”]);
     */
    #[Group('feature')]
    public function test_it_re_adds_order_line_after_removing_it_by_setting_units_to_zero(): void
    {
        // GIVEN
        $productVariant = ProductVariantScenarioBuilder::productVariantMotherMakeAndPersist(
            stock: ProductVariantStockMother::make(50),
            productId: ProductScenarioBuilder::productMotherMakeAndPersist(
                productCategoryId: ProductCategoryScenarioBuilder::productCategoryMotherMakeAndPersist()->id(),
            )->id(),
        );
        LoginScenarioBuilder::customerMotherAndUserMotherMakeAndPersistAndLoginSanctum();

        // Add product to cart with 5 units
        $this->putJson(self::ENDPOINT, [
            'product_variant_id' => $productVariant->id()->value(),
            'units' => 5,
        ]);

        // Remove product by setting units to 0
        $this->putJson(self::ENDPOINT, [
            'product_variant_id' => $productVariant->id()->value(),
            'units' => 0,
        ]);

        // WHEN
        // Add it back with 3 units
        $response = $this->putJson(self::ENDPOINT, [
            'product_variant_id' => $productVariant->id()->value(),
            'units' => 3,
        ]);

        // THEN
        $response->assertOk();
        $this->assertValidOrderWithOneLineJsonStructure($response);
        $response->assertJsonFragment([
            'status' => 'success',
            'message' => 'Product updated in cart.',
        ]);

        $responseLine = collect($response->json('data.lines'))
            ->firstWhere('product_variant_id', $productVariant->id()->value());
        $this->assertNotFalse($responseLine);
        $this->assertEquals(3, $responseLine['units']);
    }

    #[Group('feature')]
    public function test_it_limits_units_when_stock_is_insufficient(): void
    {
        // GIVEN
        $productVariant = ProductVariantScenarioBuilder::productVariantMotherMakeAndPersist(
            stock: ProductVariantStockMother::make(7),
            productId: ProductScenarioBuilder::productMotherMakeAndPersist(
                productCategoryId: ProductCategoryScenarioBuilder::productCategoryMotherMakeAndPersist()->id(),
            )->id(),
        );
        $payload = [
            'product_variant_id' => $productVariant->id()->value(),
            'units' => 9999999,
        ];
        LoginScenarioBuilder::customerMotherAndUserMotherMakeAndPersistAndLoginSanctum();

        // WHEN
        $response = $this->putJson(self::ENDPOINT, $payload);

        // THEN
        $response->assertOk();
        $this->assertValidOrderWithOneLineJsonStructure($response);
        $response->assertJsonFragment([
            'status' => 'success',
            'message' => 'Product updated in cart.',
        ]);
        $responseLine = collect($response->json('data.lines'))
            ->firstWhere('product_variant_id', $productVariant->id()->value());
        $this->assertNotFalse($responseLine);
        $this->assertEquals(7, $responseLine['units']);
    }

    #[Group('feature')]
    public function test_it_fails_when_user_is_not_authenticated(): void
    {
        // GIVEN
        $productVariant = ProductVariantScenarioBuilder::productVariantMotherMakeAndPersist(
            stock: ProductVariantStockMother::make(5),
            productId: ProductScenarioBuilder::productMotherMakeAndPersist(
                productCategoryId: ProductCategoryScenarioBuilder::productCategoryMotherMakeAndPersist()->id(),
            )->id(),
        );
        $payload = [
            'product_variant_id' => $productVariant->id(),
            'units' => 2,
        ];

        // WHEN
        $response = $this->putJson(self::ENDPOINT, $payload);

        // THEN
        $this->assertNotAuthenticatedStatusAndAssertJson($response);
    }

    #[Group('feature')]
    public function test_it_fails_when_product_variant_does_not_exist(): void
    {
        // GIVEN
        $nonExistentProductVariantId = self::ID_NOT_EXISTING;
        $payload = [
            'product_variant_id' => $nonExistentProductVariantId,
            'units' => 3,
        ];
        LoginScenarioBuilder::customerMotherAndUserMotherMakeAndPersistAndLoginSanctum();

        // WHEN
        $response = $this->putJson(self::ENDPOINT, $payload);

        // THEN
        $response->assertNotFound();
        $response->assertJsonFragment([
            'status' => 'error',
            'message' => "No product found for variant <$nonExistentProductVariantId>.",
        ]);
    }

    #[Group('feature')]
    public function test_it_fails_when_units_are_negative(): void
    {
        // GIVEN
        $productVariant = ProductVariantScenarioBuilder::productVariantMotherMakeAndPersist(
            stock: ProductVariantStockMother::make(9),
            productId: ProductScenarioBuilder::productMotherMakeAndPersist(
                productCategoryId: ProductCategoryScenarioBuilder::productCategoryMotherMakeAndPersist()->id(),
            )->id(),
        );
        $payload = [
            'product_variant_id' => $productVariant->id()->value(),
            'units' => -5,
        ];
        LoginScenarioBuilder::customerMotherAndUserMotherMakeAndPersistAndLoginSanctum();

        // WHEN
        $response = $this->putJson(self::ENDPOINT, $payload);

        // THEN
        $response->assertUnprocessable();
        $response->assertJsonFragment([
            'status' => 'error',
            'message' => 'Product variant stock quantity <-5> must be positive.',
        ]);
    }

    #[Group('feature')]
    public function test_it_creates_independent_carts_for_each_user_on_update(): void
    {
        // GIVEN
        $productVariant = ProductVariantScenarioBuilder::productVariantMotherMakeAndPersist(
            stock: ProductVariantStockMother::make(47),
            productId: ProductScenarioBuilder::productMotherMakeAndPersist(
                productCategoryId: ProductCategoryScenarioBuilder::productCategoryMotherMakeAndPersist()->id(),
            )->id(),
        );
        LoginScenarioBuilder::customerMotherAndUserMotherMakeAndPersistAndLoginSanctum(); // user1

        // WHEN
        // user1 adds 2 units
        $user1Response = $this->putJson(self::ENDPOINT, [
            'product_variant_id' => $productVariant->id()->value(),
            'units' => 2,
        ]);

        // user2 adds 5 units
        LoginScenarioBuilder::customerMotherAndUserMotherMakeAndPersistAndLoginSanctum(); // user2
        $user2Response = $this->putJson(self::ENDPOINT, [
            'product_variant_id' => $productVariant->id()->value(),
            'units' => 5,
        ]);

        // THEN
        $user1Response->assertOk();
        $user2Response->assertOk();

        $user1Line = collect($user1Response->json('data.lines'))->firstWhere('product_variant_id', $productVariant->id()->value());
        $this->assertNotFalse($user1Line);
        $this->assertEquals(2, $user1Line['units']);

        $user2Line = collect($user2Response->json('data.lines'))->firstWhere('product_variant_id', $productVariant->id()->value());
        $this->assertNotFalse($user2Line);
        $this->assertEquals(5, $user2Line['units']);

        // We check that the carts are different (different IDs)
        $this->assertNotEquals(
            $user1Response->json('data.id'),
            $user2Response->json('data.id'),
        );
    }

    #[Group('integration')]
    public function test_update_cart_validation_fails_when_required_fields_are_missing(): void
    {
        LoginScenarioBuilder::customerMotherAndUserMotherMakeAndPersistAndLoginSanctum();

        $response = $this->putJson(self::ENDPOINT, []);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'status',
            'message',
            'errors' => [
                'product_variant_id',
                'units',
            ],
        ]);
    }
}
