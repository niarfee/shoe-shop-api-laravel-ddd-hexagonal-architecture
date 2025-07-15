<?php

declare(strict_types=1);

namespace Src\Shop\Product\Application\Response\Presenter;

use Src\Shop\Product\Application\Response\Dto\ProductResponseDTO;
use Src\Shop\Product\Application\Response\Dto\ProductVariantResponseDTO;

final class ProductResponsePresenter
{
    public function toArray(ProductResponseDTO $dto): array
    {
        return [
            'id' => $dto->id,
            'product_category_id' => $dto->productCategoryId,
            'name' => $dto->name,
            'description' => $dto->description,
            'price' => $dto->price,
            'price_with_symbol' => $dto->priceWithSymbol,
            'variants' => array_map(fn (ProductVariantResponseDTO $variantDto) => [
                'id' => $variantDto->id,
                'size' => $variantDto->size,
                'color' => [
                    'name' => $variantDto->colorName,
                    'hex_code' => $variantDto->colorHexCode,
                ],
                'stock' => $variantDto->stock,
            ], $dto->variants),
        ];
    }

    /**
     * @param ProductResponseDTO[] $dtos
     */
    public function toCollection(array $dtos): array
    {
        return array_map(fn (ProductResponseDTO $dto) => $this->toArray($dto), $dtos);
    }
}
