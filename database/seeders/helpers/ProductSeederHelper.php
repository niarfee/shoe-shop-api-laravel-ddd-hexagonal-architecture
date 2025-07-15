<?php

declare(strict_types=1);

namespace Database\Seeders\Helpers;

use Src\Shop\Product\Domain\Product;
use Src\Shop\Product\Domain\ProductVariant;
use Src\Shop\Product\Domain\ValueObject\ProductId;
use Src\Shop\Product\Domain\ValueObject\ProductVariantColor;
use Src\Shop\Product\Domain\ValueObject\ProductVariantSize;
use Src\Shop\Product\Infrastructure\Persistence\Eloquent\ProductEloquentModel;
use Src\Shop\Product\Infrastructure\Persistence\Eloquent\ProductVariantEloquentModel;
use Tests\Src\Shop\Product\Domain\ProductMother;
use Tests\Src\Shop\Product\Domain\ProductVariantMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\ProductCategoryIdMother;

class ProductSeederHelper extends SeederHelper
{
    public static function getFreeSizeAndColorForProduct(Product $product): ?array
    {
        $sizesAvailable = ProductVariantSize::sizesAvailable();
        $colorNamesAvailable = ProductVariantColor::colorNamesAvailable();
        shuffle($sizesAvailable);
        shuffle($colorNamesAvailable);

        foreach ($sizesAvailable as $size) {
            $size = new ProductVariantSize($size);
            foreach ($colorNamesAvailable as $color) {
                $color = new ProductVariantColor($color);
                if (!$product->hasVariant($size, $color)) {
                    return ['size' => $size, 'color' => $color];
                }
            }
        }

        return null;
    }

    public function buildFake(
        array $productCategories,
        int $quantityProducts,
        array $quantityRandomProductsVariants = [1, 2, 3],
    ): array {
        $productsWithVariants = [];

        foreach (range(1, $quantityProducts) as $_) {
            $productId = ProductId::generate();

            $product = ProductMother::make(
                id: $productId,
                productCategoryId: ProductCategoryIdMother::make(
                    fake()->randomElement($productCategories)->id()->value(),
                ),
            );

            $quantityProductsVariants = fake()->randomElement($quantityRandomProductsVariants);

            foreach (range(1, $quantityProductsVariants) as $_) {
                $sizeAndColorAvailables = self::getFreeSizeAndColorForProduct($product);
                if ($sizeAndColorAvailables === null) {
                    break;
                }
                $variant = ProductVariantMother::make(
                    productId: $productId,
                    size: $sizeAndColorAvailables['size'],
                    color: $sizeAndColorAvailables['color'],
                );
                $product->addVariant($variant);
            }

            $productsWithVariants[] = $product;
        }

        return $productsWithVariants;
    }

    public function fromDomainListToArrayList(array $productsWithVariants): array
    {
        $productsWithVariantsArray = [];

        foreach ($productsWithVariants as $product) {
            $productsWithVariantsArray[] = [
                'id' => $product->id()->value(),
                'product_category_id' => $product->productCategoryId()->value(),
                'name' => $product->name()->value(),
                'description' => $product->description()->value(),
                'price' => $product->price()->value(),
                'variants' => array_map(
                    fn (ProductVariant $variant) => [
                        'id' => $variant->id()->value(),
                        'product_id' => $variant->productId()->value(),
                        'size' => $variant->size()->value(),
                        'color' => $variant->color()->value(),
                        'stock' => $variant->stock()->value(),
                    ],
                    $product->variants(),
                ),
            ];
        }

        return $productsWithVariantsArray;
    }

    public function persistList(array $productsWithVariants): void
    {
        parent::persistListWithSubModelBase(
            modelClass: ProductEloquentModel::class,
            subModelClass: ProductVariantEloquentModel::class,
            items: $productsWithVariants,
            keySubModel: 'variants',
            keyFk: 'product_id',
        );
    }
}
