<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Order\Domain\ValueObject;

use Src\Shop\Order\Domain\ValueObject\OrderId;

final class OrderIdMother
{
    public static function make(?string $value = null): OrderId
    {
        return new OrderId($value ?? OrderId::generate()->value());
    }
}
