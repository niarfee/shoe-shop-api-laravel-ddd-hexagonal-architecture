<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\Shared\Infrastructure\Http\ApiResponse;
use Src\Shared\Infrastructure\Http\HttpStatusEnum;
use Src\Shop\ProductCategory\Application\GetAllProductCategoriesUseCase;
use Src\Shop\ProductCategory\Application\Response\Presenter\ProductCategoryResponsePresenter;

final class GetAllProductCategoriesController extends Controller
{
    public function __construct(
        private GetAllProductCategoriesUseCase $getAllProductCategoriesUseCase,
        private ProductCategoryResponsePresenter $productCategoryResponsePresenter,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $productCategories = $this->getAllProductCategoriesUseCase->__invoke();

        return ApiResponse::success(
            data: $this->productCategoryResponsePresenter->toCollection($productCategories),
            message: 'Product categories successfully retrieved.',
            httpStatus: HttpStatusEnum::Ok,
        );
    }
}
