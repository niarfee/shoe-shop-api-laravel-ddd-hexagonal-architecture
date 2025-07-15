<?php

declare(strict_types=1);

namespace Src\Shop\Product\Infrastructure\Repository;

use Src\Shop\Product\Domain\Exception\ProductVariantNotFoundException;
use Src\Shop\Product\Domain\ProductVariant;
use Src\Shop\Product\Domain\ProductVariantRepositoryInterface;
use Src\Shop\Product\Infrastructure\Mapper\ProductVariantMapper;
use Src\Shop\Product\Infrastructure\Persistence\Eloquent\ProductVariantEloquentModel;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantId;

class EloquentProductVariantRepository implements ProductVariantRepositoryInterface
{
    public function __construct(
        private ProductVariantMapper $productVariantMapper,
    ) {
    }

    public function findById(ProductVariantId $id): ProductVariant
    {
        $productVariantEloquent = ProductVariantEloquentModel::find($id->value());
        if (!$productVariantEloquent) {
            throw new ProductVariantNotFoundException($id);
        }
        return $this->productVariantMapper->fromEloquentToDomain($productVariantEloquent);
    }

    public function save(ProductVariant $productVariant): void
    {
        $existing = ProductVariantEloquentModel::find($productVariant->id()->value());
        $productVariantEloquent = $this->productVariantMapper->fromDomainToEloquent($productVariant, $existing);

        $productVariantEloquent->save();
    }
}
