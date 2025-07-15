<?php

declare(strict_types=1);

namespace Tests\Utils\Scenarios;

use Src\Shop\Product\Domain\ProductVariant;
use Src\Shop\Product\Domain\ValueObject\ProductId;
use Src\Shop\Product\Domain\ValueObject\ProductVariantColor;
use Src\Shop\Product\Domain\ValueObject\ProductVariantSize;
use Src\Shop\Product\Infrastructure\Persistence\Eloquent\ProductVariantEloquentModel;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantId;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantStock;
use Tests\Src\Shop\Product\Domain\ProductVariantMother;

final class ProductVariantScenarioBuilder extends ScenarioBuilder
{
    public static function productVariantMotherMakeAndPersist(
        ?ProductVariantId $id = null,
        ?ProductId $productId = null,
        ?ProductVariantSize $size = null,
        ?ProductVariantColor $color = null,
        ?ProductVariantStock $stock = null,
    ): ProductVariant {
        $variant = ProductVariantMother::make(
            id: $id,
            productId: $productId,
            size: $size,
            color: $color,
            stock: $stock,
        );

        ProductVariantEloquentModel::factory()->create([
            'id' => $variant->id()->value(),
            'product_id' => $variant->productId()->value(),
            'size' => $variant->size()->value(),
            'color' => $variant->color()->value(),
            'stock' => $variant->stock()->value(),
        ]);

        return $variant;
    }
}
