<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\Shared\Infrastructure\Http\ApiResponse;
use Src\Shared\Infrastructure\Http\HttpStatusEnum;
use Src\Shop\Product\Application\GetProductUseCase;
use Src\Shop\Product\Application\Response\Presenter\ProductResponsePresenter;

final class GetProductController extends Controller
{
    public function __construct(
        private GetProductUseCase $getProductUseCase,
        private ProductResponsePresenter $productResponsePresenter,
    ) {
    }

    public function __invoke(string $productId): JsonResponse
    {
        $productResponseDTO = $this->getProductUseCase->__invoke($productId);

        return ApiResponse::success(
            data: $this->productResponsePresenter->toArray($productResponseDTO),
            message: 'Product successfully retrieved.',
            httpStatus: HttpStatusEnum::Ok,
        );
    }
}
