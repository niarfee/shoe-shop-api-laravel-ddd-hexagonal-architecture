<?php

declare(strict_types=1);

namespace Src\Shop\Product\Application\Response\Mapper;

use Src\Shop\Product\Application\Response\Dto\ProductResponseDTO;
use Src\Shop\Product\Application\Response\Dto\ProductVariantResponseDTO;
use Src\Shop\Product\Domain\Product;
use Src\Shop\Product\Domain\ProductVariant;

final class ProductResponseMapper
{
    public function map(Product $product): ProductResponseDTO
    {
        $variantDTOs = array_map(
            fn (ProductVariant $variant) => new ProductVariantResponseDTO(
                id: $variant->id()->value(),
                productId: $variant->productId()->value(),
                size: $variant->size()->value(),
                colorName: $variant->color()->value(),
                colorHexCode: $variant->color()->hexCode(),
                stock: $variant->stock()->value(),
            ),
            $product->variants(),
        );

        return new ProductResponseDTO(
            id: $product->id()->value(),
            productCategoryId: $product->productCategoryId()->value(),
            name: $product->name()->value(),
            description: $product->description()->value(),
            price: $product->price()->value(),
            priceWithSymbol: $product->price()->valueWithSymbol(),
            variants: $variantDTOs,
        );
    }

    /**
     * @return ProductResponseDTO[]
     */
    public function mapMany(array $products): array
    {
        return array_map(fn (Product $product) => $this->map($product), $products);
    }
}
