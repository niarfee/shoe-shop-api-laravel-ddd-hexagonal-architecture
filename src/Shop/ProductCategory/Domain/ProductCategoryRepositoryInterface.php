<?php

declare(strict_types=1);

namespace Src\Shop\ProductCategory\Domain;

interface ProductCategoryRepositoryInterface
{
    /**
     * Retrieves all product categories.
     *
     * @throws NoProductCategoriesExistException If no product categories are found.
     * @return ProductCategory[]
     *
     */
    public function findAll(): array;
}
