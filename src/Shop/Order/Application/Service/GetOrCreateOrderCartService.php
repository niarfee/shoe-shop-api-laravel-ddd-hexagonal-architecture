<?php

declare(strict_types=1);

namespace Src\Shop\Order\Application\Service;

use Src\Shop\Order\Domain\Order;
use Src\Shop\Order\Domain\OrderRepositoryInterface;
use Src\Shop\Shared\Domain\ValueObject\CustomerId;

final class GetOrCreateOrderCartService
{
    public function __construct(private OrderRepositoryInterface $orderRepository)
    {
    }

    public function __invoke(CustomerId $customerId): Order
    {
        $orderCart = $this->orderRepository->searchCartByCustomerId($customerId);
        if ($orderCart instanceof Order) {
            return $orderCart;
        }

        $orderCart = Order::createEmptyOrderCart($customerId);
        $this->orderRepository->save($orderCart);

        return $orderCart;
    }
}
