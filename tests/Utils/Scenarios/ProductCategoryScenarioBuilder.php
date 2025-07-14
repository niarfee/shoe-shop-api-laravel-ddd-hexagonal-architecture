<?php

declare(strict_types=1);

namespace Tests\Utils\Scenarios;

use Src\Shop\ProductCategory\Domain\ProductCategory;
use Src\Shop\ProductCategory\Domain\ValueObject\ProductCategoryName;
use Src\Shop\ProductCategory\Domain\ValueObject\ProductCategorySlug;
use Src\Shop\ProductCategory\Infrastructure\Persistence\Eloquent\ProductCategoryEloquentModel;
use Src\Shop\Shared\Domain\ValueObject\ProductCategoryId;
use Tests\Src\Shop\ProductCategory\Domain\ProductCategoryMother;

final class ProductCategoryScenarioBuilder extends ScenarioBuilder
{
    public static function productCategoryMotherMakeAndPersist(
        ?ProductCategoryId $id = null,
        ?ProductCategoryName $name = null,
        ?ProductCategorySlug $slug = null,
    ): ProductCategory {
        $category = ProductCategoryMother::make(
            id: $id,
            name: $name,
            slug: $slug,
        );

        ProductCategoryEloquentModel::factory()->create([
            'id' => $category->id()->value(),
            'name' => $category->name()->value(),
            'slug' => $category->slug()->value(),
        ]);

        return $category;
    }

    public static function productCategoryTruncateTable(): void
    {
        self::truncateTable(ProductCategoryEloquentModel::class);
    }
}
