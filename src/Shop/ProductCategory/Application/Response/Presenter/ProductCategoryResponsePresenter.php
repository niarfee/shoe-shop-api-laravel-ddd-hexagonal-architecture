<?php

declare(strict_types=1);

namespace Src\Shop\ProductCategory\Application\Response\Presenter;

use Src\Shop\ProductCategory\Application\Response\Dto\ProductCategoryResponseDTO;

final class ProductCategoryResponsePresenter
{
    public function toArray(ProductCategoryResponseDTO $dto): array
    {
        return [
            'id' => $dto->id,
            'name' => $dto->name,
            'slug' => $dto->slug,
        ];
    }

    /**
     * @param ProductCategoryResponseDTO[] $dtos
     */
    public function toCollection(array $dtos): array
    {
        return array_map(fn (ProductCategoryResponseDTO $dto) => $this->toArray($dto), $dtos);
    }
}
