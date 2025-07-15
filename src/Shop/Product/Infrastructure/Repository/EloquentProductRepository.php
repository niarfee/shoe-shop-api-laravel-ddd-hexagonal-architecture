<?php

declare(strict_types=1);

namespace Src\Shop\Product\Infrastructure\Repository;

use Src\Shop\Product\Domain\Exception\ProductNotFoundByVariantIdException;
use Src\Shop\Product\Domain\Exception\ProductNotFoundException;
use Src\Shop\Product\Domain\Product;
use Src\Shop\Product\Domain\ProductRepositoryInterface;
use Src\Shop\Product\Domain\ValueObject\ProductId;
use Src\Shop\Product\Infrastructure\Mapper\ProductMapper;
use Src\Shop\Product\Infrastructure\Persistence\Eloquent\ProductEloquentModel;
use Src\Shop\ProductCategory\Domain\Exception\ProductCategoryNotFoundException;
use Src\Shop\ProductCategory\Infrastructure\Persistence\Eloquent\ProductCategoryEloquentModel;
use Src\Shop\Shared\Domain\ValueObject\ProductCategoryId;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantId;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private ProductMapper $productMapper,
    ) {
    }

    /**
     * @return Product[]
     */
    public function searchByProductCategoryId(ProductCategoryId $productCategoryId): array
    {
        $productCategory = ProductCategoryEloquentModel::find($productCategoryId->value());
        if (!$productCategory) {
            throw new ProductCategoryNotFoundException($productCategoryId);
        }

        return ProductEloquentModel::with('variants')
            ->where('product_category_id', $productCategoryId->value())
            ->get()
            ->map(fn ($productEloquent) => $this->productMapper->fromEloquentToDomain($productEloquent))
            ->toArray();
    }

    public function findById(ProductId $id): Product
    {
        $productEloquent = ProductEloquentModel::with('variants')->find($id->value());
        if (!$productEloquent) {
            throw new ProductNotFoundException($id);
        }

        return $this->productMapper->fromEloquentToDomain($productEloquent);
    }

    public function findByProductVariantId(ProductVariantId $productVariantId): Product
    {
        $productEloquent = ProductEloquentModel::whereHas('variants', function ($query) use ($productVariantId) {
            $query->where('id', $productVariantId->value());
        })->first();
        if (!$productEloquent) {
            throw new ProductNotFoundByVariantIdException($productVariantId);
        }

        return $this->productMapper->fromEloquentToDomain($productEloquent);
    }
}
