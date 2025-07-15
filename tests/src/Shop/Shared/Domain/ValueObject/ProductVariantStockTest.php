<?php

declare(strict_types=1);

namespace Tests\Shop\Shared\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Group;
use Src\Shop\Shared\Domain\Exception\ProductVariantStockNegativeException;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantStock;
use Tests\Utils\UnitPhpUnitTestCase;

final class ProductVariantStockTest extends UnitPhpUnitTestCase
{
    private const VALID_STOCK = 10;
    private const ZERO_STOCK = 0;
    private const NEGATIVE_STOCK = -5;

    #[Group('unit')]
    public function test_create_with_valid_stock(): void
    {
        // WHEN
        $stock = new ProductVariantStock(self::VALID_STOCK);

        // THEN
        $this->assertSame(self::VALID_STOCK, $stock->value());
    }

    #[Group('unit')]
    public function test_create_with_zero_stock(): void
    {
        // WHEN
        $stock = new ProductVariantStock(self::ZERO_STOCK);

        // THEN
        $this->assertSame(self::ZERO_STOCK, $stock->value());
    }

    #[Group('unit')]
    public function test_negative_stock_throws_exception(): void
    {
        // GIVEN
        $this->expectException(ProductVariantStockNegativeException::class);
        $this->expectExceptionMessage(
            'Product variant stock <' . self::NEGATIVE_STOCK . '> must be positive.',
        );

        // WHEN
        new ProductVariantStock(self::NEGATIVE_STOCK);
    }

    #[Group('unit')]
    public function test_equals_instances(): void
    {
        // GIVEN
        $stock1 = new ProductVariantStock(self::VALID_STOCK);
        $stock2 = new ProductVariantStock(self::VALID_STOCK);
        $differentStock = new ProductVariantStock(20);

        // THEN
        $this->assertTrue($stock1->equals($stock2));
        $this->assertFalse($stock1->equals($differentStock));
    }
}
