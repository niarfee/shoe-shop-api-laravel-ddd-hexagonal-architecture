<?php

declare(strict_types=1);

namespace Src\Shop\ProductCategory\Infrastructure\Repository;

use Src\Shop\ProductCategory\Domain\Exception\NoProductCategoriesExistException;
use Src\Shop\ProductCategory\Domain\ProductCategoryRepositoryInterface;
use Src\Shop\ProductCategory\Infrastructure\Mapper\ProductCategoryMapper;
use Src\Shop\ProductCategory\Infrastructure\Persistence\Eloquent\ProductCategoryEloquentModel;

class EloquentProductCategoryRepository implements ProductCategoryRepositoryInterface
{
    public function __construct(
        private ProductCategoryMapper $productCategoryMapper,
    ) {
    }

    /**
     * @return ProductCategory[]
     */
    public function findAll(): array
    {
        $productCategories = ProductCategoryEloquentModel::all()
            ->map(fn ($model) => $this->productCategoryMapper->fromEloquentToDomain($model))
            ->toArray();
        if (empty($productCategories)) {
            throw new NoProductCategoriesExistException();
        }
        return $productCategories;
    }
}
