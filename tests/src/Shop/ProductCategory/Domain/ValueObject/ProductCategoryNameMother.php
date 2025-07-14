<?php

declare(strict_types=1);

namespace Tests\Src\Shop\ProductCategory\Domain\ValueObject;

use Src\Shop\ProductCategory\Domain\ValueObject\ProductCategoryName;
use Tests\Src\Shop\Shared\Domain\MotherCreator;

final class ProductCategoryNameMother
{
    public static function make(?string $value = null): ProductCategoryName
    {
        return new ProductCategoryName($value ?? MotherCreator::faker()->words(2, true));
    }
}
