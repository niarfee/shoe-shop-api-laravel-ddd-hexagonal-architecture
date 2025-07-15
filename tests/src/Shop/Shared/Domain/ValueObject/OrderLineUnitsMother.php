<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Shared\Domain\ValueObject;

use Src\Shop\Shared\Domain\ValueObject\OrderLineUnits;
use Tests\Src\Shop\Shared\Domain\MotherCreator;

final class OrderLineUnitsMother
{
    public static function make(?int $value = null): OrderLineUnits
    {
        return new OrderLineUnits($value ?? MotherCreator::faker()->numberBetween(1, 10));
    }
}
