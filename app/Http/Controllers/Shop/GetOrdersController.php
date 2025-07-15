<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\Shared\Infrastructure\Http\ApiResponse;
use Src\Shared\Infrastructure\Http\HttpStatusEnum;
use Src\Shop\Order\Application\GetOrdersUseCase;
use Src\Shop\Order\Application\Response\Presenter\OrderResponsePresenter;

final class GetOrdersController extends Controller
{
    public function __construct(
        private GetOrdersUseCase $getOrdersUseCase,
        private OrderResponsePresenter $orderResponsePresenter,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $ordersResponseDTO = $this->getOrdersUseCase->__invoke();

        return ApiResponse::success(
            data: $this->orderResponsePresenter->toCollection($ordersResponseDTO),
            message: 'Orders successfully retrieved.',
            httpStatus: HttpStatusEnum::Ok,
        );
    }
}
