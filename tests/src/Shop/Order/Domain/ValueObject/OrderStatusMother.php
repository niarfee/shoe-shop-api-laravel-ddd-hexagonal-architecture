<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Order\Domain\ValueObject;

use Src\Shop\Order\Domain\ValueObject\OrderStatus;
use Tests\Src\Shop\Shared\Domain\MotherCreator;

final class OrderStatusMother
{
    public static function make(?int $value = null): OrderStatus
    {
        return new OrderStatus(
            $value ?? MotherCreator::faker()->randomElement(OrderStatus::statusValuesAvailable()),
        );
    }
}
