<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Product\Domain\ValueObject;

use Src\Shop\Product\Domain\ValueObject\ProductId;

final class ProductIdMother
{
    public static function make(?string $value = null): ProductId
    {
        return new ProductId($value ?? ProductId::generate()->value());
    }
}
