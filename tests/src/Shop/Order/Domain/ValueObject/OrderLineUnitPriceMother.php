<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Order\Domain\ValueObject;

use Src\Shop\Order\Domain\ValueObject\OrderLineUnitPrice;
use Tests\Src\Shop\Shared\Domain\MotherCreator;

final class OrderLineUnitPriceMother
{
    public static function make(?float $value = null): OrderLineUnitPrice
    {
        return new OrderLineUnitPrice($value ?? MotherCreator::faker()->randomFloat(2, 1, 50));
    }
}
