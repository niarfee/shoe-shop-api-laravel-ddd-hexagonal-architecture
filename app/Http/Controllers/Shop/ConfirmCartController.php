<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\Shared\Infrastructure\Http\ApiResponse;
use Src\Shared\Infrastructure\Http\HttpStatusEnum;
use Src\Shop\Order\Application\ConfirmCartUseCase;
use Src\Shop\Order\Application\Response\Presenter\OrderResponsePresenter;

final class ConfirmCartController extends Controller
{
    public function __construct(
        private ConfirmCartUseCase $confirmCartUseCase,
        private OrderResponsePresenter $orderResponsePresenter,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $orderCartResponseDTO = $this->confirmCartUseCase->__invoke();

        return ApiResponse::success(
            data: $this->orderResponsePresenter->toArray($orderCartResponseDTO),
            message: 'Order successfully confirmed.',
            httpStatus: HttpStatusEnum::Ok,
        );
    }
}
