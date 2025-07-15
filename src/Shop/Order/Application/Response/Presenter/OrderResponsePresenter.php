<?php

declare(strict_types=1);

namespace Src\Shop\Order\Application\Response\Presenter;

use Src\Shop\Order\Application\Response\Dto\OrderLineResponseDTO;
use Src\Shop\Order\Application\Response\Dto\OrderResponseDTO;

final class OrderResponsePresenter
{
    public function toArray(OrderResponseDTO $dto): array
    {
        return [
            'id' => $dto->id,
            'customer_id' => $dto->customerId,
            'status' => [
                'value' => $dto->statusValue,
                'label' => $dto->statusLabel,
            ],
            'total_price' => $dto->totalPrice,
            'total_price_with_symbol' => $dto->totalPriceWithSymbol,
            'lines' => array_map(fn (OrderLineResponseDTO $lineDto) => [
                'id' => $lineDto->id,
                'product_variant_id' => $lineDto->productVariantId,
                'units' => $lineDto->units,
                'unit_price' => $lineDto->unitPrice,
                'unit_price_with_symbol' => $lineDto->unitPriceWithSymbol,
                'total_price' => $lineDto->totalPrice,
                'total_price_with_symbol' => $lineDto->totalPriceWithSymbol,
            ], $dto->lines),
        ];
    }

    /**
     * @param OrderResponseDTO[] $dtos
     */
    public function toCollection(array $dtos): array
    {
        return array_map(fn (OrderResponseDTO $dto) => $this->toArray($dto), $dtos);
    }
}
