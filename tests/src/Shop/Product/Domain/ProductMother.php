<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Product\Domain;

use Src\Shop\Product\Domain\Product;
use Src\Shop\Product\Domain\ValueObject\ProductDescription;
use Src\Shop\Product\Domain\ValueObject\ProductId;
use Src\Shop\Product\Domain\ValueObject\ProductName;
use Src\Shop\Product\Domain\ValueObject\ProductPrice;
use Src\Shop\Shared\Domain\ValueObject\ProductCategoryId;
use Tests\Src\Shop\Product\Domain\ValueObject\ProductDescriptionMother;
use Tests\Src\Shop\Product\Domain\ValueObject\ProductIdMother;
use Tests\Src\Shop\Product\Domain\ValueObject\ProductNameMother;
use Tests\Src\Shop\Product\Domain\ValueObject\ProductPriceMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\ProductCategoryIdMother;

final class ProductMother
{
    public static function make(
        ?ProductId $id = null,
        ?ProductCategoryId $productCategoryId = null,
        ?ProductName $name = null,
        ?ProductDescription $description = null,
        ?ProductPrice $price = null,
        array $variants = [],
    ): Product {
        return Product::create(
            id: $id ?? ProductIdMother::make(),
            productCategoryId: $productCategoryId ?? ProductCategoryIdMother::make(),
            name: $name ?? ProductNameMother::make(),
            description: $description ?? ProductDescriptionMother::make(),
            price: $price ?? ProductPriceMother::make(),
            variants: $variants,
        );
    }
}
