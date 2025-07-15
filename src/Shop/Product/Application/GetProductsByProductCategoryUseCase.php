<?php

declare(strict_types=1);

namespace Src\Shop\Product\Application;

use Src\Shop\Product\Application\Response\Mapper\ProductResponseMapper;
use Src\Shop\Product\Domain\ProductRepositoryInterface;
use Src\Shop\Shared\Domain\ValueObject\ProductCategoryId;

final class GetProductsByProductCategoryUseCase
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private ProductResponseMapper $productResponseMapper,
    ) {
    }

    /**
     * @return ProductResponseDTO[]
     */
    public function __invoke(string $productCategoryId): array
    {
        $productCategoryId = new ProductCategoryId($productCategoryId);
        $products = $this->productRepository->searchByProductCategoryId($productCategoryId);
        return $this->productResponseMapper->mapMany($products);
    }
}
