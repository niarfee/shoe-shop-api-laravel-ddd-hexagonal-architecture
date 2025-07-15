<?php

declare(strict_types=1);

namespace Src\Shop\Order\Application\Response\Dto;

final class OrderLineResponseDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $orderId,
        public readonly string $productVariantId,
        public readonly int $units,
        public readonly float $unitPrice,
        public readonly string $unitPriceWithSymbol,
        public readonly float $totalPrice,
        public readonly string $totalPriceWithSymbol,
    ) {
    }
}
