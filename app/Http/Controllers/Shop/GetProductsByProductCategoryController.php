<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\Shared\Infrastructure\Http\ApiResponse;
use Src\Shared\Infrastructure\Http\HttpStatusEnum;
use Src\Shop\Product\Application\GetProductsByProductCategoryUseCase;
use Src\Shop\Product\Application\Response\Presenter\ProductResponsePresenter;

final class GetProductsByProductCategoryController extends Controller
{
    public function __construct(
        private GetProductsByProductCategoryUseCase $getProductsByProductCategoryUseCase,
        private ProductResponsePresenter $productResponsePresenter,
    ) {
    }

    public function __invoke(string $productCategoryId): JsonResponse
    {
        $productsResponseDTO = $this->getProductsByProductCategoryUseCase->__invoke($productCategoryId);

        return ApiResponse::success(
            data: $this->productResponsePresenter->toCollection($productsResponseDTO),
            message: 'Products successfully retrieved.',
            httpStatus: HttpStatusEnum::Ok,
        );
    }
}
