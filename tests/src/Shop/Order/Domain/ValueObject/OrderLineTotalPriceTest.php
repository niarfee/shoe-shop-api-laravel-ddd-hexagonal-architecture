<?php

declare(strict_types=1);

namespace Tests\Shop\Order\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Group;
use Src\Shop\Order\Domain\ValueObject\OrderLineTotalPrice;
use Src\Shop\Order\Domain\ValueObject\OrderLineUnitPrice;
use Src\Shop\Shared\Domain\ValueObject\OrderLineUnits;
use Tests\Utils\UnitPhpUnitTestCase;

final class OrderLineTotalPriceTest extends UnitPhpUnitTestCase
{
    private const float UNIT_PRICE = 10.50;
    private const int UNITS = 3;
    private const float EXPECTED_TOTAL = 31.50; // 10.50 * 3
    private const string CURRENCY_SYMBOL = '€';

    #[Group('unit')]
    public function test_calculate_total_price(): void
    {
        // GIVEN
        $unitPrice = new OrderLineUnitPrice(self::UNIT_PRICE);
        $units = new OrderLineUnits(self::UNITS);

        // WHEN
        $totalPrice = new OrderLineTotalPrice($unitPrice, $units);

        // THEN
        $this->assertSame(self::EXPECTED_TOTAL, $totalPrice->value());
    }

    #[Group('unit')]
    public function test_zero_units_returns_zero_total(): void
    {
        // GIVEN
        $unitPrice = new OrderLineUnitPrice(self::UNIT_PRICE);
        $zeroUnits = new OrderLineUnits(0);

        // WHEN
        $totalPrice = new OrderLineTotalPrice($unitPrice, $zeroUnits);

        // THEN
        $this->assertSame(0.0, $totalPrice->value());
    }

    #[Group('unit')]
    public function test_equals(): void
    {
        // GIVEN
        $unitPrice1 = new OrderLineUnitPrice(self::UNIT_PRICE);
        $units1 = new OrderLineUnits(self::UNITS);
        $totalPrice1 = new OrderLineTotalPrice($unitPrice1, $units1);

        $unitPrice2 = new OrderLineUnitPrice(self::UNIT_PRICE);
        $units2 = new OrderLineUnits(self::UNITS);
        $totalPrice2 = new OrderLineTotalPrice($unitPrice2, $units2);

        $differentPrice = new OrderLineTotalPrice(new OrderLineUnitPrice(5.0), new OrderLineUnits(2));

        // THEN
        $this->assertTrue($totalPrice1->equals($totalPrice2));
        $this->assertFalse($totalPrice1->equals($differentPrice));
    }

    #[Group('unit')]
    public function test_value_with_symbol(): void
    {
        // GIVEN
        $unitPrice = new OrderLineUnitPrice(self::UNIT_PRICE);
        $units = new OrderLineUnits(self::UNITS);
        $totalPrice = new OrderLineTotalPrice($unitPrice, $units);

        $expected = self::EXPECTED_TOTAL . ' ' . self::CURRENCY_SYMBOL;

        // WHEN
        $result = $totalPrice->valueWithSymbol();

        // THEN
        $this->assertSame($expected, $result);
    }

    #[Group('unit')]
    public function test_value_with_custom_symbol(): void
    {
        // GIVEN
        $customSymbol = '$';
        $unitPrice = new OrderLineUnitPrice(self::UNIT_PRICE);
        $units = new OrderLineUnits(self::UNITS);
        $totalPrice = new OrderLineTotalPrice($unitPrice, $units);

        $expected = self::EXPECTED_TOTAL . ' ' . $customSymbol;

        // WHEN
        $result = $totalPrice->valueWithSymbol($customSymbol);

        // THEN
        $this->assertSame($expected, $result);
    }
}
