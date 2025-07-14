<?php

declare(strict_types=1);

namespace Src\Shop\ProductCategory\Application\Response\Mapper;

use Src\Shop\ProductCategory\Application\Response\Dto\ProductCategoryResponseDTO;
use Src\Shop\ProductCategory\Domain\ProductCategory;

final class ProductCategoryResponseMapper
{
    public function map(ProductCategory $productCategory): ProductCategoryResponseDTO
    {
        return new ProductCategoryResponseDTO(
            $productCategory->id()->value(),
            $productCategory->name()->value(),
            $productCategory->slug()->value(),
        );
    }

    /**
     * @return ProductCategoryResponseDTO[]
     */
    public function mapMany(array $productCategories): array
    {
        return array_map(fn (ProductCategory $productCategory) => $this->map($productCategory), $productCategories);
    }
}
