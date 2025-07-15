<?php

declare(strict_types=1);

namespace Src\Shop\Order\Infrastructure\Mapper;

use Src\Shop\Order\Domain\OrderLine;
use Src\Shop\Order\Domain\ValueObject\OrderId;
use Src\Shop\Order\Domain\ValueObject\OrderLineId;
use Src\Shop\Order\Domain\ValueObject\OrderLineUnitPrice;
use Src\Shop\Order\Infrastructure\Persistence\Eloquent\OrderLineEloquentModel;
use Src\Shop\Shared\Domain\ValueObject\OrderLineUnits;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantId;

final class OrderLineMapper
{
    public function fromEloquentToDomain(OrderLineEloquentModel $orderLineEloquent): OrderLine
    {
        return OrderLine::create(
            id: new OrderLineId($orderLineEloquent->id),
            orderId: new OrderId($orderLineEloquent->order_id),
            productVariantId: new ProductVariantId($orderLineEloquent->product_variant_id),
            units: new OrderLineUnits($orderLineEloquent->units),
            unitPrice: new OrderLineUnitPrice($orderLineEloquent->unit_price),
        );
    }

    public function fromDomainToEloquent(
        OrderLine $orderLine,
        ?OrderLineEloquentModel $orderLineEloquent = null,
    ): OrderLineEloquentModel {
        $orderLineEloquent ??= new OrderLineEloquentModel();

        $orderLineEloquent->id = $orderLine->id()->value();
        $orderLineEloquent->order_id = $orderLine->orderId()->value();
        $orderLineEloquent->product_variant_id = $orderLine->productVariantId()->value();
        $orderLineEloquent->units = $orderLine->units()->value();
        $orderLineEloquent->unit_price = $orderLine->unitPrice()->value();

        return $orderLineEloquent;
    }
}
