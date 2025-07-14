<?php

declare(strict_types=1);

namespace Database\Seeders\Helpers;

use Src\Shop\ProductCategory\Infrastructure\Persistence\Eloquent\ProductCategoryEloquentModel;
use Tests\Src\Shop\ProductCategory\Domain\ProductCategoryMother;

class ProductCategorySeederHelper extends SeederHelper
{
    public function buildFake(int $quantity): array
    {
        // return ProductCategoryEloquentModel::factory()->count($quantity)->make()->toArray();

        $productCategories = [];

        foreach (range(1, $quantity) as $_) {
            $productCategories[] = ProductCategoryMother::make();
        }

        return $productCategories;
    }

    public function fromDomainListToArrayList(array $productCategories): array
    {
        $productCategoriesArray = [];

        foreach ($productCategories as $productCategory) {
            $productCategoriesArray[] = [
                'id' => $productCategory->id()->value(),
                'name' => $productCategory->name()->value(),
                'slug' => $productCategory->slug()->value(),
            ];
        }

        return $productCategoriesArray;
    }

    public function persistList(array $productCategories): void
    {
        parent::persistListBase(ProductCategoryEloquentModel::class, $productCategories);
    }
}
