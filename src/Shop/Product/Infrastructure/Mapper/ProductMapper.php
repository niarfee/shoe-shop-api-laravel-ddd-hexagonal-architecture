<?php

declare(strict_types=1);

namespace Src\Shop\Product\Infrastructure\Mapper;

use Src\Shop\Product\Domain\Product;
use Src\Shop\Product\Domain\ValueObject\ProductDescription;
use Src\Shop\Product\Domain\ValueObject\ProductId;
use Src\Shop\Product\Domain\ValueObject\ProductName;
use Src\Shop\Product\Domain\ValueObject\ProductPrice;
use Src\Shop\Product\Infrastructure\Persistence\Eloquent\ProductEloquentModel;
use Src\Shop\Shared\Domain\ValueObject\ProductCategoryId;

final class ProductMapper
{
    public function __construct(
        private ProductVariantMapper $productVariantMapper,
    ) {
    }

    public function fromEloquentToDomain(ProductEloquentModel $productEloquent): Product
    {
        $variants = array_map(
            fn ($variant) => $this->productVariantMapper->fromEloquentToDomain($variant),
            $productEloquent->variants->all(),
        );

        return Product::create(
            id: new ProductId($productEloquent->id),
            productCategoryId: new ProductCategoryId($productEloquent->product_category_id),
            name: new ProductName($productEloquent->name),
            description: new ProductDescription($productEloquent->description),
            price: new ProductPrice($productEloquent->price),
            variants: $variants,
        );
    }
}
