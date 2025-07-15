<?php

declare(strict_types=1);

namespace Src\Shop\Product\Application;

use Src\Shop\Product\Application\Response\Dto\ProductResponseDTO;
use Src\Shop\Product\Application\Response\Mapper\ProductResponseMapper;
use Src\Shop\Product\Domain\ProductRepositoryInterface;
use Src\Shop\Product\Domain\ValueObject\ProductId;

final class GetProductUseCase
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private ProductResponseMapper $productResponseMapper,
    ) {
    }

    public function __invoke(string $productId): ProductResponseDTO
    {
        $productId = new ProductId($productId);
        $product = $this->productRepository->findById($productId);
        return $this->productResponseMapper->map($product);
    }
}
