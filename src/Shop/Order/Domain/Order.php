<?php

declare(strict_types=1);

namespace Src\Shop\Order\Domain;

use Src\Shop\Order\Domain\Exception\OrderAlreadyContainsOrderLineForProductVariantException;
use Src\Shop\Order\Domain\Exception\OrderDoesNotContainOrderLineException;
use Src\Shop\Order\Domain\Exception\OrderDoesNotContainOrderLineForProductVariantException;
use Src\Shop\Order\Domain\Exception\OrderLineDoesNotBelongToOrderException;
use Src\Shop\Order\Domain\Exception\OrderLineNotFoundException;
use Src\Shop\Order\Domain\Exception\OrderLineVariantIdDoesNotMatchException;
use Src\Shop\Order\Domain\Exception\OrderNotInCartException;
use Src\Shop\Order\Domain\ValueObject\OrderId;
use Src\Shop\Order\Domain\ValueObject\OrderLineId;
use Src\Shop\Order\Domain\ValueObject\OrderLineUnitPrice;
use Src\Shop\Order\Domain\ValueObject\OrderStatus;
use Src\Shop\Order\Domain\ValueObject\OrderTotalPrice;
use Src\Shop\Shared\Domain\ValueObject\CustomerId;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantId;
use Src\Shop\Shared\Domain\ValueObject\StockRequest;

final class Order
{
    /**
     * @param OrderLine[] $variants
     */
    private array $lines = [];

    private function __construct(
        private readonly OrderId $id,
        private readonly CustomerId $customerId,
        private OrderStatus $status,
        private OrderTotalPrice $totalPrice,
    ) {
    }

    public static function create(
        OrderId $id,
        CustomerId $customerId,
        OrderStatus $status,
        OrderTotalPrice $totalPrice,
        array $lines = [],
    ): self {
        $order = new self($id, $customerId, $status, $totalPrice);
        $order->addLines($lines);
        return $order;
    }

    public static function createEmptyOrderCart(CustomerId $customerId): self
    {
        return self::create(
            id: OrderId::generate(),
            customerId: $customerId,
            status: OrderStatus::inCart(),
            totalPrice: OrderTotalPrice::zero(),
        );
    }

    // Public state mutation

    /**
     * @param OrderLine[] $variants
     */
    public function addLines(array $lines): void
    {
        foreach ($lines as $line) {
            $this->addLine($line);
        }
    }

    public function addLine(OrderLine $orderLine): void
    {
        $this->ensureBelongsToOrder($orderLine);
        $this->ensureNotHasLine($orderLine->productVariantId());

        $this->lines[] = $orderLine;
        $this->recalculateTotalPrice();
    }

    public function updateLineUnitsBasedOnStock(
        OrderLineId $orderLineId,
        StockRequest $stockRequest,
    ): ?OrderLine {
        $this->ensureIsNotInCart();

        $orderLine = $this->lineById($orderLineId);

        $orderLine->updateUnitsBasedOnStock($stockRequest);
        if ($orderLine->units()->isEmpty()) {
            $this->removeLine($orderLineId);
            return null;
        }
        $this->updateLine($orderLine);

        return $orderLine;
    }

    public function updateLineUnitPrice(
        OrderLineId $orderLineId,
        OrderLineUnitPrice $orderLineUnitPrice,
    ): OrderLine {
        $this->ensureIsNotInCart();

        $orderLine = $this->lineById($orderLineId);

        $orderLine->updateUnitPrice($orderLineUnitPrice);
        $this->updateLine($orderLine);

        return $orderLine;
    }

    public function changeStatusToPendingShipment(): void
    {
        $this->changeStatus(OrderStatus::pendingShipment());
    }

    // Public query methods

    public function lineById(OrderLineId $orderLineId): ?OrderLine
    {
        foreach ($this->lines as $line) {
            if ($line->id()->value() === $orderLineId->value()) {
                return $line;
            }
        }
        throw new OrderLineNotFoundException($orderLineId);
    }

    public function lineByProductVariantId(ProductVariantId $productVariantId): ?OrderLine
    {
        foreach ($this->lines as $line) {
            if ($line->productVariantId()->value() === $productVariantId->value()) {
                return $line;
            }
        }
        return null;
    }

    public function hasLine(ProductVariantId $productVariantId): bool
    {
        return $this->lineByProductVariantId($productVariantId) !== null;
    }

    public function isEmpty(): bool
    {
        return empty($this->lines);
    }

    // Public accessors

    public function lines(): array
    {
        return $this->lines;
    }

    public function id(): OrderId
    {
        return $this->id;
    }

    public function customerId(): CustomerId
    {
        return $this->customerId;
    }

    public function status(): OrderStatus
    {
        return $this->status;
    }

    public function totalPrice(): OrderTotalPrice
    {
        return $this->totalPrice;
    }

    // Private state mutation

    /**
     * Updates an existing order line with new values.
     *
     * @note Exceptions are unreachable in current design but kept for robustness.
     *
     * @param OrderLine $orderLineToUpdate The order line with updated values
     *
     * @throws OrderDoesNotContainOrderLineForProductVariantException If the order line doesn't exist
     * @throws OrderLineVariantIdDoesNotMatchException If attempting to change the product variant ID
     */
    private function updateLine(OrderLine $orderLineToUpdate): void
    {
        $this->ensureBelongsToOrder($orderLineToUpdate);

        foreach ($this->lines as $index => $line) {
            if ($line->id()->equals($orderLineToUpdate->id())) {
                $this->ensureOrderLineUniqueness($line, $orderLineToUpdate);
                $this->lines[$index] = $orderLineToUpdate;
                $this->recalculateTotalPrice();
                return;
            }
        }

        throw new OrderDoesNotContainOrderLineForProductVariantException($this->id, $orderLineToUpdate->productVariantId());
    }

    /**
     * Removes an order line from the order.
     *
     * @note Exception is unreachable in current design but kept for robustness.
     *
     * @param OrderLineId $orderLineId The ID of the line to remove
     *
     * @throws OrderDoesNotContainOrderLineException If the order line doesn't exist
     */
    private function removeLine(OrderLineId $orderLineId): void
    {
        foreach ($this->lines as $index => $line) {
            if ($line->id()->value() === $orderLineId->value()) {
                unset($this->lines[$index]);
                $this->recalculateTotalPrice();
                return;
            }
        }
        throw new OrderDoesNotContainOrderLineException($this->id, $line->id());
    }

    private function changeStatus(OrderStatus $newStatus): void
    {
        $this->status = $newStatus;
    }

    private function recalculateTotalPrice(): void
    {
        $totalPrice = 0.0;
        foreach ($this->lines as $line) {
            $totalPrice += $line->totalPrice()->value();
        }
        $this->totalPrice = new OrderTotalPrice($totalPrice);
    }

    // Private query methods

    private function isInCart(): bool
    {
        return $this->status()->equals(OrderStatus::inCart());
    }

    // Private guard clauses

    private function ensureIsNotInCart(): void
    {
        if (!$this->isInCart()) {
            throw new OrderNotInCartException($this->status());
        }
    }

    /**
     * Ensures product variant ID consistency during updates.
     *
     * @note Exception is unreachable in current design but kept for robustness.
     *
     * @param OrderLine $currentOrderLine The existing order line
     * @param OrderLine $updatedOrderLine The order line with potential updates
     *
     * @throws OrderLineVariantIdDoesNotMatchException If the product variant IDs don't match
     */
    private function ensureOrderLineUniqueness(OrderLine $currentOrderLine, OrderLine $otherOrderLine): void
    {
        if (!$currentOrderLine->productVariantId()->equals($otherOrderLine->productVariantId())) {
            throw new OrderLineVariantIdDoesNotMatchException(
                $currentOrderLine->id(),
                $currentOrderLine->productVariantId(),
                $otherOrderLine->productVariantId(),
            );
        }
    }

    private function ensureBelongsToOrder(OrderLine $orderLine): void
    {
        if (!$orderLine->belongsToOrder($this->id)) {
            throw new OrderLineDoesNotBelongToOrderException(
                $orderLine->id(),
                $orderLine->orderId(),
                $this->id,
            );
        }
    }

    private function ensureNotHasLine(ProductVariantId $productVariantId): void
    {
        if ($this->hasLine($productVariantId)) {
            throw new OrderAlreadyContainsOrderLineForProductVariantException($this->id, $productVariantId);
        }
    }
}
