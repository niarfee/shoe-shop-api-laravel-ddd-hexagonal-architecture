<?php

declare(strict_types=1);

namespace Src\Shop\ProductCategory\Infrastructure\Mapper;

use Src\Shop\ProductCategory\Domain\ProductCategory;
use Src\Shop\ProductCategory\Domain\ValueObject\ProductCategoryName;
use Src\Shop\ProductCategory\Domain\ValueObject\ProductCategorySlug;
use Src\Shop\ProductCategory\Infrastructure\Persistence\Eloquent\ProductCategoryEloquentModel;
use Src\Shop\Shared\Domain\ValueObject\ProductCategoryId;

final class ProductCategoryMapper
{
    public function fromEloquentToDomain(ProductCategoryEloquentModel $productCategoryEloquent): ProductCategory
    {
        return ProductCategory::create(
            id: new ProductCategoryId($productCategoryEloquent->id),
            name: new ProductCategoryName($productCategoryEloquent->name),
            slug: new ProductCategorySlug($productCategoryEloquent->slug),
        );
    }
}
