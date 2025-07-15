<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\Shared\Infrastructure\Http\ApiResponse;
use Src\Shared\Infrastructure\Http\HttpStatusEnum;
use Src\Shop\Order\Application\GetCartUseCase;
use Src\Shop\Order\Application\Response\Presenter\OrderResponsePresenter;

final class GetCartController extends Controller
{
    public function __construct(
        private GetCartUseCase $getCartUseCase,
        private OrderResponsePresenter $orderResponsePresenter,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $orderCartResponseDTO = $this->getCartUseCase->__invoke();

        return ApiResponse::success(
            data: $this->orderResponsePresenter->toArray($orderCartResponseDTO),
            message: 'Cart successfully retrieved.',
            httpStatus: HttpStatusEnum::Ok,
        );
    }
}
