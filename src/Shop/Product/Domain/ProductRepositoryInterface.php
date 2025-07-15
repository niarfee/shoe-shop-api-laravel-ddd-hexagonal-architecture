<?php

declare(strict_types=1);

namespace Src\Shop\Product\Domain;

use Src\Shop\Product\Domain\ValueObject\ProductId;
use Src\Shop\Shared\Domain\ValueObject\ProductCategoryId;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantId;

interface ProductRepositoryInterface
{
    /**
     * Retrieves all products belonging to a given product category.
     *
     * @throws ProductCategoryNotFoundException If the product category does not exist.
     * @return Product[]
     *
     */
    public function searchByProductCategoryId(ProductCategoryId $productCategoryId): array;

    /**
     * Finds a product by its ID.
     *
     * @throws ProductNotFoundException If the product does not exist.
     */
    public function findById(ProductId $id): Product;

    /**
     * Finds a product by the ID of one of its variants.
     *
     * @throws ProductNotFoundByVariantIdException If no product is associated with the given variant ID.
     */
    public function findByProductVariantId(ProductVariantId $productVariantId): Product;
}
