<?php

declare(strict_types=1);

namespace Src\Shop\Shared\Domain\ValueObject;

final class StockRequest
{
    public function __construct(
        private readonly ProductVariantStockRequested $stockRequested,
        private readonly ProductVariantStock $stockAvailable,
    ) {
    }

    public static function create(ProductVariantStockRequested $stockRequested, ProductVariantStock $stockAvailable): self
    {
        $stockRequest = new self($stockRequested, $stockAvailable);
        return $stockRequest;
    }

    public function toOrderLineUnits(): OrderLineUnits
    {
        $requested = $this->stockRequested->value();
        $available = $this->stockAvailable->value();
        $finalUnits = min($requested, $available);

        return new OrderLineUnits($finalUnits);
    }
}
