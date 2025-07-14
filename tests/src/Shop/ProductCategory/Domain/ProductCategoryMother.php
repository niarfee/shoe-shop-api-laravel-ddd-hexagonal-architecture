<?php

declare(strict_types=1);

namespace Tests\Src\Shop\ProductCategory\Domain;

use Src\Shop\ProductCategory\Domain\ProductCategory;
use Src\Shop\ProductCategory\Domain\ValueObject\ProductCategoryName;
use Src\Shop\ProductCategory\Domain\ValueObject\ProductCategorySlug;
use Src\Shop\Shared\Domain\ValueObject\ProductCategoryId;
use Tests\Src\Shop\ProductCategory\Domain\ValueObject\ProductCategoryNameMother;
use Tests\Src\Shop\ProductCategory\Domain\ValueObject\ProductCategorySlugMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\ProductCategoryIdMother;

final class ProductCategoryMother
{
    public static function make(
        ?ProductCategoryId $id = null,
        ?ProductCategoryName $name = null,
        ?ProductCategorySlug $slug = null,
    ): ProductCategory {
        return ProductCategory::create(
            id: $id ?? ProductCategoryIdMother::make(),
            name: $name ?? ProductCategoryNameMother::make(),
            slug: $slug ?? ProductCategorySlugMother::make(),
        );
    }
}
