<?php

declare(strict_types=1);

namespace Src\Shop\Order\Infrastructure\Mapper;

use Src\Shop\Order\Domain\Order;
use Src\Shop\Order\Domain\ValueObject\OrderId;
use Src\Shop\Order\Domain\ValueObject\OrderStatus;
use Src\Shop\Order\Domain\ValueObject\OrderTotalPrice;
use Src\Shop\Order\Infrastructure\Persistence\Eloquent\OrderEloquentModel;
use Src\Shop\Shared\Domain\ValueObject\CustomerId;

final class OrderMapper
{
    public function __construct(
        private OrderLineMapper $orderLineMapper,
    ) {
    }

    public function fromEloquentToDomain(OrderEloquentModel $orderEloquent): Order
    {
        $lines = array_map(
            fn ($line) => $this->orderLineMapper->fromEloquentToDomain($line),
            $orderEloquent->lines->all(),
        );

        return Order::create(
            id: new OrderId($orderEloquent->id),
            customerId: new CustomerId($orderEloquent->customer_id),
            status: new OrderStatus($orderEloquent->status),
            totalPrice: new OrderTotalPrice($orderEloquent->total_price),
            lines: $lines,
        );
    }

    public function fromDomainToEloquent(
        Order $order,
        ?OrderEloquentModel $orderEloquent = null,
    ): OrderEloquentModel {
        $orderEloquent ??= new OrderEloquentModel();

        $orderEloquent->id = $order->id()->value();
        $orderEloquent->customer_id = $order->customerId()->value();
        $orderEloquent->status = $order->status()->value();
        $orderEloquent->total_price = $order->totalPrice()->value();

        return $orderEloquent;
    }
}
