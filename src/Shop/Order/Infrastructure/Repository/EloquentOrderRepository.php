<?php

declare(strict_types=1);

namespace Src\Shop\Order\Infrastructure\Repository;

use Src\Shop\Order\Domain\Order;
use Src\Shop\Order\Domain\OrderRepositoryInterface;
use Src\Shop\Order\Domain\ValueObject\OrderStatus;
use Src\Shop\Order\Infrastructure\Mapper\OrderMapper;
use Src\Shop\Order\Infrastructure\Persistence\Eloquent\OrderEloquentModel;
use Src\Shop\Shared\Domain\ValueObject\CustomerId;

class EloquentOrderRepository implements OrderRepositoryInterface
{
    public function __construct(
        private OrderMapper $orderMapper,
    ) {
    }

    public function searchCartByCustomerId(CustomerId $customerId): ?Order
    {
        $orderEloquent = OrderEloquentModel::with('lines')
            ->where('customer_id', $customerId->value())
            ->where('status', OrderStatus::IN_CART)
            ->first();

        return $orderEloquent
            ? $this->orderMapper->fromEloquentToDomain($orderEloquent)
            : null;
    }

    public function save(Order $order): void
    {
        $existing = OrderEloquentModel::find($order->id()->value());
        $orderEloquent = $this->orderMapper->fromDomainToEloquent($order, $existing);

        $orderEloquent->save();
    }

    public function searchOrdersByCustomerId(CustomerId $customerId): array
    {
        return OrderEloquentModel::with('lines')
            ->where('customer_id', $customerId->value())
            ->where('status', '!=', OrderStatus::IN_CART)
            ->get()
            ->map(fn ($orderEloquent) => $this->orderMapper->fromEloquentToDomain($orderEloquent))
            ->toArray();
    }
}
