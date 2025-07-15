<?php

declare(strict_types=1);

namespace Src\Shop\Order\Application\Response\Dto;

final class OrderResponseDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $customerId,
        public readonly int $statusValue,
        public readonly string $statusLabel,
        public readonly float $totalPrice,
        public readonly string $totalPriceWithSymbol,
        /** @var OrderLineResponse[] */
        public readonly array $lines,
    ) {
    }
}
