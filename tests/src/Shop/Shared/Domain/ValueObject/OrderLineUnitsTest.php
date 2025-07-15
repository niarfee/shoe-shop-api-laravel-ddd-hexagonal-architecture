<?php

declare(strict_types=1);

namespace Tests\Shop\Shared\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Group;
use Src\Shop\Shared\Domain\Exception\OrderLineUnitNegativeException;
use Src\Shop\Shared\Domain\ValueObject\OrderLineUnits;
use Tests\Utils\UnitPhpUnitTestCase;

final class OrderLineUnitsTest extends UnitPhpUnitTestCase
{
    private const int VALID_UNITS = 5;
    private const int ZERO_UNITS = 0;
    private const int NEGATIVE_UNITS = -1;
    private const int ADDITIONAL_UNITS = 3;

    #[Group('unit')]
    public function test_create_valid_units(): void
    {
        // WHEN
        $units = new OrderLineUnits(self::VALID_UNITS);

        // THEN
        $this->assertSame(self::VALID_UNITS, $units->value());
    }

    #[Group('unit')]
    public function test_create_zero_units(): void
    {
        // WHEN
        $units = new OrderLineUnits(self::ZERO_UNITS);

        // THEN
        $this->assertSame(self::ZERO_UNITS, $units->value());
        $this->assertTrue($units->isEmpty());
    }

    #[Group('unit')]
    public function test_negative_units_throws_exception(): void
    {
        // GIVEN
        $this->expectException(OrderLineUnitNegativeException::class);
        $this->expectExceptionMessage('Order line units <' . self::NEGATIVE_UNITS . '> must be positive.');

        // WHEN
        new OrderLineUnits(self::NEGATIVE_UNITS);
    }

    #[Group('unit')]
    public function test_is_empty(): void
    {
        // GIVEN
        $zeroUnits = new OrderLineUnits(self::ZERO_UNITS);
        $nonZeroUnits = new OrderLineUnits(self::VALID_UNITS);

        // THEN
        $this->assertTrue($zeroUnits->isEmpty());
        $this->assertFalse($nonZeroUnits->isEmpty());
    }
}
