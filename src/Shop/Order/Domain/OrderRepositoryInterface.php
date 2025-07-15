<?php

declare(strict_types=1);

namespace Src\Shop\Order\Domain;

use Src\Shop\Shared\Domain\ValueObject\CustomerId;

interface OrderRepositoryInterface
{
    /**
     * Searches for the shopping cart (order in progress) of the given customer.
     *
     * @return Order|null The cart order if it exists, or null otherwise.
     */
    public function searchCartByCustomerId(CustomerId $customerId): ?Order;

    /**
     * Persists the given order.
     */
    public function save(Order $order): void;

    /**
     * Retrieves all completed orders associated with the given customer.
     *
     * @return Order[] An array of orders, excluding the shopping cart.
     */
    public function searchOrdersByCustomerId(CustomerId $customerId): array;
}
