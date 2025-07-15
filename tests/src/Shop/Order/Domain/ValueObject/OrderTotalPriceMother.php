<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Order\Domain\ValueObject;

use Src\Shop\Order\Domain\ValueObject\OrderTotalPrice;
use Tests\Src\Shop\Shared\Domain\MotherCreator;

final class OrderTotalPriceMother
{
    public static function make(?float $value = null): OrderTotalPrice
    {
        return new OrderTotalPrice($value ?? MotherCreator::faker()->randomFloat(2, 1, 100));
    }
}
