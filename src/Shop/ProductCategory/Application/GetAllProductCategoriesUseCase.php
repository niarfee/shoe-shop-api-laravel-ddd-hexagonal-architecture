<?php

declare(strict_types=1);

namespace Src\Shop\ProductCategory\Application;

use Src\Shop\ProductCategory\Application\Response\Mapper\ProductCategoryResponseMapper;
use Src\Shop\ProductCategory\Domain\ProductCategoryRepositoryInterface;

final class GetAllProductCategoriesUseCase
{
    public function __construct(
        private ProductCategoryRepositoryInterface $productCategoryRepository,
        private ProductCategoryResponseMapper $productCategoryResponseMapper,
    ) {
    }

    /**
     * @return ProductCategoryResponseDTO[]
     */
    public function __invoke(): array
    {
        $productCategories = $this->productCategoryRepository->findAll();
        return $this->productCategoryResponseMapper->mapMany($productCategories);
    }
}
