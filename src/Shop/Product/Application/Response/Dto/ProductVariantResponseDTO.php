<?php

declare(strict_types=1);

namespace Src\Shop\Product\Application\Response\Dto;

final class ProductVariantResponseDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $productId,
        public readonly int $size,
        public readonly string $colorName,
        public readonly string $colorHexCode,
        public readonly int $stock,
    ) {
    }
}
