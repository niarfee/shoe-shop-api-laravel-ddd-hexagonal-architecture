<?php

declare(strict_types=1);

namespace Src\Shop\Product\Domain;

use Src\Shop\Shared\Domain\ValueObject\ProductVariantId;

interface ProductVariantRepositoryInterface
{
    /**
     * Finds a product variant by its ID.
     *
     * @throws ProductVariantNotFoundException If the product variant does not exist.
     */
    public function findById(ProductVariantId $id): ProductVariant;

    /**
     * Persists the given product variant.
     */
    public function save(ProductVariant $productVariant): void;
}
