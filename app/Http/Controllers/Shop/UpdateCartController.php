<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Src\Shared\Infrastructure\Http\ApiResponse;
use Src\Shared\Infrastructure\Http\HttpStatusEnum;
use Src\Shop\Order\Application\Response\Presenter\OrderResponsePresenter;
use Src\Shop\Order\Application\UpdateCartUseCase;
use Src\Shop\Shared\Domain\ValueObject\CustomerId;

final class UpdateCartController extends Controller
{
    public function __construct(
        private UpdateCartUseCase $updateCartUseCase,
        private OrderResponsePresenter $orderResponsePresenter,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $validatedInput = $request->validate([
            'product_variant_id' => ['required', 'string'],
            'units' => ['required', 'integer'],
        ]);

        $customerId = new CustomerId((string) Auth::user()->customer_id);

        $orderResponseDTO = $this->updateCartUseCase->__invoke(
            $validatedInput['product_variant_id'],
            (int) $validatedInput['units'],
            $customerId,
        );

        return ApiResponse::success(
            data: $this->orderResponsePresenter->toArray($orderResponseDTO),
            message: 'Product updated in cart.',
            httpStatus: HttpStatusEnum::Ok,
        );
    }
}
