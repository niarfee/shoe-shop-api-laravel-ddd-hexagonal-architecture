<?php

declare(strict_types=1);

namespace Src\Shop\ProductCategory\Domain;

use Src\Shop\ProductCategory\Domain\ValueObject\ProductCategoryName;
use Src\Shop\ProductCategory\Domain\ValueObject\ProductCategorySlug;
use Src\Shop\Shared\Domain\ValueObject\ProductCategoryId;

final class ProductCategory
{
    private function __construct(
        private readonly ProductCategoryId $id,
        private readonly ProductCategoryName $name,
        private readonly ProductCategorySlug $slug,
    ) {
    }

    public static function create(ProductCategoryId $id, ProductCategoryName $name, ProductCategorySlug $slug): self
    {
        $productCategory = new self($id, $name, $slug);
        return $productCategory;
    }

    // Public accessors

    public function id(): ProductCategoryId
    {
        return $this->id;
    }

    public function name(): ProductCategoryName
    {
        return $this->name;
    }

    public function slug(): ProductCategorySlug
    {
        return $this->slug;
    }
}
