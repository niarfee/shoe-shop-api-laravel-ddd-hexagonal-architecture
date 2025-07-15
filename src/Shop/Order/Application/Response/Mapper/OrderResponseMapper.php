<?php

declare(strict_types=1);

namespace Src\Shop\Order\Application\Response\Mapper;

use Src\Shop\Order\Application\Response\Dto\OrderLineResponseDTO;
use Src\Shop\Order\Application\Response\Dto\OrderResponseDTO;
use Src\Shop\Order\Domain\Order;
use Src\Shop\Order\Domain\OrderLine;

final class OrderResponseMapper
{
    public function map(Order $order): OrderResponseDTO
    {
        $lineResponses = array_map(
            fn (OrderLine $line) => new OrderLineResponseDTO(
                $line->id()->value(),
                $line->orderId()->value(),
                $line->productVariantId()->value(),
                $line->units()->value(),
                $line->unitPrice()->value(),
                $line->unitPrice()->valueWithSymbol(),
                $line->totalPrice()->value(),
                $line->totalPrice()->valueWithSymbol(),
            ),
            $order->lines(),
        );

        return new OrderResponseDTO(
            id: $order->id()->value(),
            customerId: $order->customerId()->value(),
            statusValue: $order->status()->value(),
            statusLabel: $order->status()->label(),
            totalPrice: $order->totalPrice()->value(),
            totalPriceWithSymbol: $order->totalPrice()->valueWithSymbol(),
            lines: $lineResponses,
        );
    }

    /**
     * @return OrderResponseDTO[]
     */
    public function mapMany(array $orders): array
    {
        return array_map(fn (Order $order) => $this->map($order), $orders);
    }
}
