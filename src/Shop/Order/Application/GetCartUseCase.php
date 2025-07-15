<?php

declare(strict_types=1);

namespace Src\Shop\Order\Application;

use Illuminate\Support\Facades\Auth;
use Src\Shop\Order\Application\Response\Dto\OrderResponseDTO;
use Src\Shop\Order\Application\Response\Mapper\OrderResponseMapper;
use Src\Shop\Order\Application\Service\GetOrCreateOrderCartService;
use Src\Shop\Shared\Domain\ValueObject\CustomerId;

final class GetCartUseCase
{
    public function __construct(
        private GetOrCreateOrderCartService $getOrCreateOrderCartService,
        private OrderResponseMapper $orderResponseMapper,
    ) {
    }

    public function __invoke(): OrderResponseDTO
    {
        $customerId = new CustomerId(Auth::user()->customer_id);
        $orderCart = $this->getOrCreateOrderCartService->__invoke($customerId);
        return $this->orderResponseMapper->map($orderCart);
    }
}
