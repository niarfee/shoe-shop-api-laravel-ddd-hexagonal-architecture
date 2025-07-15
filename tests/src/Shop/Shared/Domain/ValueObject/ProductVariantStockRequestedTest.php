<?php

declare(strict_types=1);

namespace Tests\Shop\Shared\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Group;
use Src\Shop\Shared\Domain\Exception\ProductVariantStockRequestedNegativeException;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantStockRequested;
use Tests\Utils\UnitPhpUnitTestCase;

final class ProductVariantStockRequestedTest extends UnitPhpUnitTestCase
{
    private const VALID_QUANTITY = 5;
    private const ZERO_QUANTITY = 0;
    private const NEGATIVE_QUANTITY = -1;

    #[Group('unit')]
    public function test_create_valid_quantity(): void
    {
        // GIVEN
        $quantity = self::VALID_QUANTITY;

        // WHEN
        $stockRequested = new ProductVariantStockRequested($quantity);

        // THEN
        $this->assertSame($quantity, $stockRequested->value());
    }

    #[Group('unit')]
    public function test_create_zero_quantity(): void
    {
        // GIVEN
        $quantity = self::ZERO_QUANTITY;

        // WHEN
        $stockRequested = new ProductVariantStockRequested($quantity);

        // THEN (no exception should be thrown)
        $this->assertSame($quantity, $stockRequested->value());
    }

    #[Group('unit')]
    public function test_create_negative_quantity_throws_exception(): void
    {
        // GIVEN
        $this->expectException(ProductVariantStockRequestedNegativeException::class);
        $this->expectExceptionMessage(
            'Product variant stock quantity <' . self::NEGATIVE_QUANTITY . '> must be positive.',
        );

        // WHEN
        new ProductVariantStockRequested(self::NEGATIVE_QUANTITY);
    }

    #[Group('unit')]
    public function test_value_method_returns_correct_value(): void
    {
        // GIVEN
        $expectedValue = self::VALID_QUANTITY;
        $stockRequested = new ProductVariantStockRequested($expectedValue);

        // WHEN
        $value = $stockRequested->value();

        // THEN
        $this->assertSame($expectedValue, $value);
    }

    #[Group('unit')]
    public function test_equals_instances(): void
    {
        // GIVEN
        $stock1 = new ProductVariantStockRequested(self::VALID_QUANTITY);
        $stock2 = new ProductVariantStockRequested(self::VALID_QUANTITY);
        $differentStock = new ProductVariantStockRequested(10);

        // THEN
        $this->assertTrue($stock1->equals($stock2));
        $this->assertFalse($stock1->equals($differentStock));
    }
}
