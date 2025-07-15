<?php

declare(strict_types=1);

namespace Src\Shop\Order\Domain;

use Src\Shop\Order\Domain\ValueObject\OrderId;
use Src\Shop\Order\Domain\ValueObject\OrderLineId;
use Src\Shop\Order\Domain\ValueObject\OrderLineTotalPrice;
use Src\Shop\Order\Domain\ValueObject\OrderLineUnitPrice;
use Src\Shop\Shared\Domain\ValueObject\OrderLineUnits;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantId;
use Src\Shop\Shared\Domain\ValueObject\StockRequest;

final class OrderLine
{
    private function __construct(
        private readonly OrderLineId $id,
        private readonly OrderId $orderId,
        private readonly ProductVariantId $productVariantId,
        private OrderLineUnits $units,
        private OrderLineUnitPrice $unitPrice,
    ) {
    }

    public static function create(
        OrderLineId $id,
        OrderId $orderId,
        ProductVariantId $productVariantId,
        OrderLineUnits $units,
        OrderLineUnitPrice $unitPrice,
    ): self {
        return new self($id, $orderId, $productVariantId, $units, $unitPrice);
    }

    public static function createBasedOnStock(
        OrderLineId $id,
        OrderId $orderId,
        ProductVariantId $productVariantId,
        StockRequest $stockRequest,
        OrderLineUnitPrice $unitPrice,
    ): self {
        $unitsAvailable = $stockRequest->toOrderLineUnits();
        return self::create($id, $orderId, $productVariantId, $unitsAvailable, $unitPrice);
    }

    // Public state mutation

    public function updateUnitsBasedOnStock(
        StockRequest $stockRequest,
    ): void {
        $unitsAvailable = $stockRequest->toOrderLineUnits();
        $this->units = $unitsAvailable;
    }

    public function updateUnitPrice(OrderLineUnitPrice $newUnitPrice): void
    {
        $this->unitPrice = $newUnitPrice;
    }

    // Public query methods

    public function belongsToOrder(OrderId $orderId): bool
    {
        return $this->orderId()->equals($orderId);
    }

    // Public accessors

    public function id(): OrderLineId
    {
        return $this->id;
    }

    public function orderId(): OrderId
    {
        return $this->orderId;
    }

    public function productVariantId(): ProductVariantId
    {
        return $this->productVariantId;
    }

    public function units(): OrderLineUnits
    {
        return $this->units;
    }

    public function unitPrice(): OrderLineUnitPrice
    {
        return $this->unitPrice;
    }

    public function totalPrice(): OrderLineTotalPrice
    {
        return new OrderLineTotalPrice($this->unitPrice, $this->units);
    }
}
