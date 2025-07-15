<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Product\Domain;

use Src\Shop\Product\Domain\ProductVariant;
use Src\Shop\Product\Domain\ValueObject\ProductId;
use Src\Shop\Product\Domain\ValueObject\ProductVariantColor;
use Src\Shop\Product\Domain\ValueObject\ProductVariantSize;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantId;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantStock;
use Tests\Src\Shop\Product\Domain\ValueObject\ProductIdMother;
use Tests\Src\Shop\Product\Domain\ValueObject\ProductVariantColorMother;
use Tests\Src\Shop\Product\Domain\ValueObject\ProductVariantSizeMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\ProductVariantIdMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\ProductVariantStockMother;

final class ProductVariantMother
{
    public static function make(
        ?ProductVariantId $id = null,
        ?ProductId $productId = null,
        ?ProductVariantSize $size = null,
        ?ProductVariantColor $color = null,
        ?ProductVariantStock $stock = null,
    ): ProductVariant {
        return ProductVariant::create(
            id: $id ?? ProductVariantIdMother::make(),
            productId: $productId ?? ProductIdMother::make(),
            size: $size ?? ProductVariantSizeMother::make(),
            color: $color ?? ProductVariantColorMother::make(),
            stock: $stock ?? ProductVariantStockMother::make(),
        );
    }
}
