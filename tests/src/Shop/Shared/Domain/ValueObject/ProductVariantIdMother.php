<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Shared\Domain\ValueObject;

use Src\Shop\Shared\Domain\ValueObject\ProductVariantId;

final class ProductVariantIdMother
{
    public static function make(?string $value = null): ProductVariantId
    {
        return new ProductVariantId($value ?? ProductVariantId::generate()->value());
    }
}
