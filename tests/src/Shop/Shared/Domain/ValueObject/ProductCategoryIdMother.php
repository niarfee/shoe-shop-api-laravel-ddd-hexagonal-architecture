<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Shared\Domain\ValueObject;

use Src\Shop\Shared\Domain\ValueObject\ProductCategoryId;

final class ProductCategoryIdMother
{
    public static function make(?string $value = null): ProductCategoryId
    {
        return new ProductCategoryId($value ?? ProductCategoryId::generate()->value());
    }
}
