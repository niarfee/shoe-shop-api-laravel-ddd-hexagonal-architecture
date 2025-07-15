<?php

declare(strict_types=1);

namespace Tests\Shop\Product\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Group;
use Src\Shop\Product\Domain\Exception\InvalidProductVariantSizeException;
use Src\Shop\Product\Domain\ValueObject\ProductVariantSize;
use Tests\Utils\UnitPhpUnitTestCase;

final class ProductVariantSizeTest extends UnitPhpUnitTestCase
{
    private const VALID_SIZES = [34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45];
    private const INVALID_SIZE = 33;

    #[Group('unit')]
    public function test_create_valid_size(): void
    {
        // GIVEN
        $validSize = self::VALID_SIZES[0];

        // WHEN
        $size = new ProductVariantSize($validSize);

        // THEN
        $this->assertSame($validSize, $size->value());
    }

    #[Group('unit')]
    public function test_create_invalid_size_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidProductVariantSizeException::class);
        $this->expectExceptionMessage('Invalid product variant size');

        // WHEN
        new ProductVariantSize(self::INVALID_SIZE);
    }

    #[Group('unit')]
    public function test_sizes_available_returns_correct_values(): void
    {
        // WHEN
        $availableSizes = ProductVariantSize::sizesAvailable();

        // THEN
        $this->assertSame(self::VALID_SIZES, $availableSizes);
    }

    #[Group('unit')]
    public function test_equals(): void
    {
        // GIVEN
        $size1 = new ProductVariantSize(40);
        $size2 = new ProductVariantSize(40);
        $size3 = new ProductVariantSize(42);

        // THEN
        $this->assertTrue($size1->equals($size2));
        $this->assertFalse($size1->equals($size3));
    }

    #[Group('unit')]
    public function test_value_method_returns_correct_value(): void
    {
        // GIVEN
        $expectedValue = 40;
        $size = new ProductVariantSize($expectedValue);

        // WHEN
        $value = $size->value();

        // THEN
        $this->assertSame($expectedValue, $value);
    }

    #[Group('unit')]
    public function test_all_valid_sizes_can_be_created(): void
    {
        // GIVEN
        foreach (self::VALID_SIZES as $validSize) {
            // WHEN
            $size = new ProductVariantSize($validSize);

            // THEN (no exception should be thrown)
            $this->assertSame($validSize, $size->value());
        }
    }
}
