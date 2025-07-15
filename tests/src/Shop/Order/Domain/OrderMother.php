<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Order\Domain;

use Src\Shop\Order\Domain\Order;
use Src\Shop\Order\Domain\ValueObject\OrderId;
use Src\Shop\Order\Domain\ValueObject\OrderStatus;
use Src\Shop\Order\Domain\ValueObject\OrderTotalPrice;
use Src\Shop\Shared\Domain\ValueObject\CustomerId;
use Tests\Src\Shop\Order\Domain\ValueObject\OrderIdMother;
use Tests\Src\Shop\Order\Domain\ValueObject\OrderStatusMother;
use Tests\Src\Shop\Order\Domain\ValueObject\OrderTotalPriceMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\CustomerIdMother;

final class OrderMother
{
    public static function make(
        ?OrderId $id = null,
        ?CustomerId $customerId = null,
        ?OrderStatus $status = null,
        ?OrderTotalPrice $totalPrice = null,
        array $lines = [],
    ): Order {
        return Order::create(
            id: $id ?? OrderIdMother::make(),
            customerId: $customerId ?? CustomerIdMother::make(),
            status: $status ?? OrderStatusMother::make(),
            totalPrice: $totalPrice ?? OrderTotalPriceMother::make(),
            lines: $lines,
        );
    }
}
