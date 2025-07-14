<?php

declare(strict_types=1);

namespace Tests\Src\Shop\ProductCategory\Domain\ValueObject;

use Src\Shop\ProductCategory\Domain\ValueObject\ProductCategorySlug;
use Tests\Src\Shop\Shared\Domain\MotherCreator;

final class ProductCategorySlugMother
{
    public static function make(?string $value = null): ProductCategorySlug
    {
        return new ProductCategorySlug($value ?? MotherCreator::faker()->slug);
    }
}
