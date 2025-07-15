<?php

declare(strict_types=1);

namespace Src\Shop\Order\Infrastructure\Repository;

use Src\Shop\Order\Domain\Exception\OrderLineNotFoundException;
use Src\Shop\Order\Domain\OrderLine;
use Src\Shop\Order\Domain\OrderLineRepositoryInterface;
use Src\Shop\Order\Domain\ValueObject\OrderLineId;
use Src\Shop\Order\Infrastructure\Mapper\OrderLineMapper;
use Src\Shop\Order\Infrastructure\Persistence\Eloquent\OrderLineEloquentModel;

class EloquentOrderLineRepository implements OrderLineRepositoryInterface
{
    public function __construct(
        private OrderLineMapper $orderLineMapper,
    ) {
    }

    public function save(OrderLine $orderLine): void
    {
        $existing = OrderLineEloquentModel::find($orderLine->id()->value());
        $orderLineEloquent = $this->orderLineMapper->fromDomainToEloquent($orderLine, $existing);

        $orderLineEloquent->save();
    }

    public function delete(OrderLineId $orderLineId): void
    {
        $orderLine = OrderLineEloquentModel::find($orderLineId->value());
        if (!$orderLine) {
            throw new OrderLineNotFoundException($orderLineId);
        }

        $orderLine->delete();
    }
}
