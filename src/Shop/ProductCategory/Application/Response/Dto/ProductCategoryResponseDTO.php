<?php

declare(strict_types=1);

namespace Src\Shop\ProductCategory\Application\Response\Dto;

final class ProductCategoryResponseDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $slug,
    ) {
    }
}
