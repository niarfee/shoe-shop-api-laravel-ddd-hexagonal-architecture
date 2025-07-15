<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Order\Domain\ValueObject;

use Src\Shop\Order\Domain\ValueObject\OrderLineId;

final class OrderLineIdMother
{
    public static function make(?string $value = null): OrderLineId
    {
        return new OrderLineId($value ?? OrderLineId::generate()->value());
    }
}
