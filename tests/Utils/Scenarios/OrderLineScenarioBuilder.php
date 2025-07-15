<?php

declare(strict_types=1);

namespace Tests\Utils\Scenarios;

use Src\Shop\Order\Domain\OrderLine;
use Src\Shop\Order\Domain\ValueObject\OrderId;
use Src\Shop\Order\Domain\ValueObject\OrderLineId;
use Src\Shop\Order\Domain\ValueObject\OrderLineUnitPrice;
use Src\Shop\Order\Infrastructure\Persistence\Eloquent\OrderLineEloquentModel;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantId;
use Src\Shop\Shared\Domain\ValueObject\StockRequest;
use Tests\Src\Shop\Order\Domain\OrderLineMother;

final class OrderLineScenarioBuilder extends ScenarioBuilder
{
    public static function orderLineMotherMakeAndPersist(
        ?OrderLineId $id = null,
        ?OrderId $orderId = null,
        ?ProductVariantId $productVariantId = null,
        ?StockRequest $stockRequest = null,
        ?OrderLineUnitPrice $unitPrice = null,
    ): OrderLine {
        $line = OrderLineMother::make(
            id: $id,
            orderId: $orderId,
            productVariantId: $productVariantId,
            units: $stockRequest->toOrderLineUnits(),
            unitPrice: $unitPrice,
        );

        OrderLineEloquentModel::factory()->create([
            'id' => $line->id()->value(),
            'order_id' => $line->orderId()->value(),
            'product_variant_id' => $line->productVariantId()->value(),
            'units' => $line->units()->value(),
            'unit_price' => $line->unitPrice()->value(),
        ]);

        return $line;
    }
}
