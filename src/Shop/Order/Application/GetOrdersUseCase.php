<?php

declare(strict_types=1);

namespace Src\Shop\Order\Application;

use Illuminate\Support\Facades\Auth;
use Src\Shop\Order\Application\Response\Mapper\OrderResponseMapper;
use Src\Shop\Order\Domain\OrderRepositoryInterface;
use Src\Shop\Shared\Domain\ValueObject\CustomerId;

final class GetOrdersUseCase
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private OrderResponseMapper $orderResponseMapper,
    ) {
    }

    /**
     * @return OrderResponseDTO[]
     */
    public function __invoke(): array
    {
        $customerId = new CustomerId(Auth::user()->customer_id);
        $orders = $this->orderRepository->searchOrdersByCustomerId($customerId);
        return $this->orderResponseMapper->mapMany($orders);
    }
}
