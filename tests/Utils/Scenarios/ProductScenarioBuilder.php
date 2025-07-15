<?php

declare(strict_types=1);

namespace Tests\Utils\Scenarios;

use Database\Seeders\Helpers\ProductSeederHelper;
use Exception;
use Src\Shop\Product\Domain\Product;
use Src\Shop\Product\Domain\ValueObject\ProductDescription;
use Src\Shop\Product\Domain\ValueObject\ProductId;
use Src\Shop\Product\Domain\ValueObject\ProductName;
use Src\Shop\Product\Domain\ValueObject\ProductPrice;
use Src\Shop\Product\Infrastructure\Persistence\Eloquent\ProductEloquentModel;
use Src\Shop\Shared\Domain\ValueObject\ProductCategoryId;
use Tests\Src\Shop\Product\Domain\ProductMother;
use Tests\Src\Shop\Product\Domain\ValueObject\ProductVariantColorMother;
use Tests\Src\Shop\Product\Domain\ValueObject\ProductVariantSizeMother;

final class ProductScenarioBuilder extends ScenarioBuilder
{
    public static function productMotherMakeAndPersist(
        ?ProductId $id = null,
        ?ProductCategoryId $productCategoryId = null,
        ?ProductName $name = null,
        ?ProductDescription $description = null,
        ?ProductPrice $price = null,
    ): Product {
        $product = ProductMother::make(
            id: $id,
            productCategoryId: $productCategoryId,
            name: $name,
            description: $description,
            price: $price,
        );

        ProductEloquentModel::factory()->create([
            'id' => $product->id()->value(),
            'product_category_id' => $product->productCategoryId()->value(),
            'name' => $product->name()->value(),
            'description' => $product->description()->value(),
            'price' => $product->price()->value(),
        ]);

        return $product;
    }

    public static function productMotherMakeAndPersistWithDependenciesWithNVariants(
        ProductCategoryId $productCategoryId,
        int $numberOfVariants = 4,
    ): Product {
        $product = self::productMotherMakeAndPersist(
            productCategoryId: $productCategoryId,
        );

        for ($i = 0; $i < $numberOfVariants; $i++) {
            $sizeAndColorAvailables = ProductSeederHelper::getFreeSizeAndColorForProduct($product);
            if ($sizeAndColorAvailables === null) {
                throw new Exception('No more color and size combinations available for ProductVariant.');
            }
            $product->addVariant(
                ProductVariantScenarioBuilder::productVariantMotherMakeAndPersist(
                    productId: $product->id(),
                    size: ProductVariantSizeMother::make($sizeAndColorAvailables['size']->value()),
                    color: ProductVariantColorMother::make($sizeAndColorAvailables['color']->value()),
                ),
            );
        }

        return $product;
    }
}
