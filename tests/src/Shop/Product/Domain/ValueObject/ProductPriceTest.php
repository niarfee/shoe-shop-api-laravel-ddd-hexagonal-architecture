<?php

declare(strict_types=1);

namespace Tests\Shop\Product\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Group;
use Src\Shared\Domain\Exception\InvalidPriceException;
use Src\Shop\Product\Domain\ValueObject\ProductPrice;
use Tests\Utils\UnitPhpUnitTestCase;

final class ProductPriceTest extends UnitPhpUnitTestCase
{
    private const float VALID_PRICE = 99.99;
    private const float NEGATIVE_PRICE = -10.50;
    private const float ZERO_PRICE = 0.0;

    #[Group('unit')]
    public function test_create_valid_price(): void
    {
        // GIVEN
        $price = self::VALID_PRICE;

        // WHEN
        $productPrice = new ProductPrice($price);

        // THEN
        $this->assertSame($price, $productPrice->value());
    }

    #[Group('unit')]
    public function test_create_zero_price(): void
    {
        // GIVEN
        $price = self::ZERO_PRICE;

        // WHEN
        $productPrice = new ProductPrice($price);

        // THEN (no exception should be thrown)
        $this->assertSame($price, $productPrice->value());
    }

    #[Group('unit')]
    public function test_create_negative_price_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidPriceException::class);
        $this->expectExceptionMessage(
            'The price <' . number_format(self::NEGATIVE_PRICE, 2) . '> must be positive.',
        );

        // WHEN
        new ProductPrice(self::NEGATIVE_PRICE);
    }

    #[Group('unit')]
    public function test_zero_static_method(): void
    {
        // WHEN
        $productPrice = ProductPrice::zero();

        // THEN
        $this->assertSame(0.0, $productPrice->value());
    }

    #[Group('unit')]
    public function test_value_method_returns_correct_value(): void
    {
        // GIVEN
        $expectedValue = self::VALID_PRICE;
        $productPrice = new ProductPrice($expectedValue);

        // WHEN
        $value = $productPrice->value();

        // THEN
        $this->assertSame($expectedValue, $value);
    }

    #[Group('unit')]
    public function test_value_with_symbol_default(): void
    {
        // GIVEN
        $productPrice = new ProductPrice(self::VALID_PRICE);
        $expected = number_format(self::VALID_PRICE, 2, '.', '') . ' €';

        // WHEN
        $result = $productPrice->valueWithSymbol();

        // THEN
        $this->assertSame($expected, $result);
    }

    #[Group('unit')]
    public function test_value_with_custom_symbol(): void
    {
        // GIVEN
        $productPrice = new ProductPrice(self::VALID_PRICE);
        $customSymbol = '$';
        $expected = number_format(self::VALID_PRICE, 2, '.', '') . ' ' . $customSymbol;

        // WHEN
        $result = $productPrice->valueWithSymbol($customSymbol);

        // THEN
        $this->assertSame($expected, $result);
    }

    #[Group('unit')]
    public function test_equals_instances(): void
    {
        // GIVEN
        $price1 = new ProductPrice(self::VALID_PRICE);
        $price2 = new ProductPrice(self::VALID_PRICE);
        $differentPrice = new ProductPrice(50.0);

        // THEN
        $this->assertTrue($price1->equals($price2));
        $this->assertFalse($price1->equals($differentPrice));
    }

    #[Group('unit')]
    public function test_equals_with_different_precision(): void
    {
        // GIVEN
        $price1 = new ProductPrice(10.0);
        $price2 = new ProductPrice(10.00);

        // THEN
        $this->assertTrue($price1->equals($price2));
        $this->assertTrue($price2->equals($price1));
    }
}
