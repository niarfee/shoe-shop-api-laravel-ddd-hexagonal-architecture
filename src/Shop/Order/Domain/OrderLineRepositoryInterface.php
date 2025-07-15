<?php

declare(strict_types=1);

namespace Src\Shop\Order\Domain;

use Src\Shop\Order\Domain\ValueObject\OrderLineId;

interface OrderLineRepositoryInterface
{
    /**
     * Persists the given order line.
     */
    public function save(OrderLine $orderLine): void;

    /**
     * Deletes the order line with the given ID.
     *
     * @throws OrderLineNotFoundException If the order line does not exist.
     */
    public function delete(OrderLineId $orderLineId): void;
}
