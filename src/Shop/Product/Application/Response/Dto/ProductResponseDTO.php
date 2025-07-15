<?php

declare(strict_types=1);

namespace Src\Shop\Product\Application\Response\Dto;

final class ProductResponseDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $productCategoryId,
        public readonly string $name,
        public readonly string $description,
        public readonly float $price,
        public readonly string $priceWithSymbol,
        /** @var ProductVariantResponseDTO[] */
        public readonly array $variants = [],
    ) {
    }
}
