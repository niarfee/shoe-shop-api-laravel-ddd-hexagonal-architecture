<?php

declare(strict_types=1);

namespace Tests\Shop\Order\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Group;
use Src\Shared\Domain\Exception\InvalidPriceException;
use Src\Shop\Order\Domain\ValueObject\OrderTotalPrice;
use Tests\Utils\UnitPhpUnitTestCase;

final class OrderTotalPriceTest extends UnitPhpUnitTestCase
{
    private const float VALID_PRICE = 99.99;
    private const float NEGATIVE_PRICE = -10.50;
    private const float ZERO_PRICE = 0.0;
    private const string CURRENCY_SYMBOL = '€';

    #[Group('unit')]
    public function test_create_valid_price(): void
    {
        // GIVEN
        $priceValue = self::VALID_PRICE;

        // WHEN
        $price = new OrderTotalPrice($priceValue);

        // THEN
        $this->assertSame($priceValue, $price->value());
    }

    #[Group('unit')]
    public function test_create_zero_price(): void
    {
        // GIVEN
        $priceValue = self::ZERO_PRICE;

        // WHEN
        $price = new OrderTotalPrice($priceValue);

        // THEN
        $this->assertSame($priceValue, $price->value());
    }

    #[Group('unit')]
    public function test_negative_price_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidPriceException::class);
        $this->expectExceptionMessage(
            'The price <' . number_format(self::NEGATIVE_PRICE, 2) . '> must be positive.',
        );

        // WHEN
        new OrderTotalPrice(self::NEGATIVE_PRICE);
    }

    #[Group('unit')]
    public function test_zero_static_constructor(): void
    {
        // WHEN
        $price = OrderTotalPrice::zero();

        // THEN
        $this->assertSame(0.0, $price->value());
    }

    #[Group('unit')]
    public function test_value_method_returns_correct_value(): void
    {
        // GIVEN
        $expectedValue = self::VALID_PRICE;
        $price = new OrderTotalPrice($expectedValue);

        // WHEN
        $value = $price->value();

        // THEN
        $this->assertSame($expectedValue, $value);
    }

    #[Group('unit')]
    public function test_value_with_default_symbol(): void
    {
        // GIVEN
        $price = new OrderTotalPrice(self::VALID_PRICE);
        $expected = number_format(self::VALID_PRICE, 2) . ' ' . self::CURRENCY_SYMBOL;

        // WHEN
        $result = $price->valueWithSymbol();

        // THEN
        $this->assertSame($expected, $result);
    }

    #[Group('unit')]
    public function test_value_with_custom_symbol(): void
    {
        // GIVEN
        $customSymbol = '$';
        $price = new OrderTotalPrice(self::VALID_PRICE);
        $expected = number_format(self::VALID_PRICE, 2) . ' ' . $customSymbol;

        // WHEN
        $result = $price->valueWithSymbol($customSymbol);

        // THEN
        $this->assertSame($expected, $result);
    }

    #[Group('unit')]
    public function test_equals(): void
    {
        // GIVEN
        $price1 = new OrderTotalPrice(self::VALID_PRICE);
        $price2 = new OrderTotalPrice(self::VALID_PRICE);
        $price3 = new OrderTotalPrice(50.0);

        // THEN
        $this->assertTrue($price1->equals($price2));
        $this->assertFalse($price1->equals($price3));
    }
}
