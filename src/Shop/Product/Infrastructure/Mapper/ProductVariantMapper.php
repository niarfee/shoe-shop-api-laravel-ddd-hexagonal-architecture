<?php

declare(strict_types=1);

namespace Src\Shop\Product\Infrastructure\Mapper;

use Src\Shop\Product\Domain\ProductVariant;
use Src\Shop\Product\Domain\ValueObject\ProductId;
use Src\Shop\Product\Domain\ValueObject\ProductVariantColor;
use Src\Shop\Product\Domain\ValueObject\ProductVariantSize;
use Src\Shop\Product\Infrastructure\Persistence\Eloquent\ProductVariantEloquentModel;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantId;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantStock;

final class ProductVariantMapper
{
    public function fromEloquentToDomain(ProductVariantEloquentModel $productVariantEloquent): ProductVariant
    {
        return ProductVariant::create(
            id: new ProductVariantId($productVariantEloquent->id),
            productId: new ProductId($productVariantEloquent->product_id),
            size: new ProductVariantSize($productVariantEloquent->size),
            color: new ProductVariantColor($productVariantEloquent->color),
            stock: new ProductVariantStock($productVariantEloquent->stock),
        );
    }

    public function fromDomainToEloquent(
        ProductVariant $productVariant,
        ?ProductVariantEloquentModel $productVariantEloquent = null,
    ): ProductVariantEloquentModel {
        $productVariantEloquent ??= new ProductVariantEloquentModel();

        $productVariantEloquent->id = $productVariant->id()->value();
        $productVariantEloquent->product_id = $productVariant->productId()->value();
        $productVariantEloquent->size = $productVariant->size()->value();
        $productVariantEloquent->color = $productVariant->color()->value();
        $productVariantEloquent->stock = $productVariant->stock()->value();

        return $productVariantEloquent;
    }
}
