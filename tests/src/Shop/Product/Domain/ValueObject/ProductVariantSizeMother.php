<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Product\Domain\ValueObject;

use Src\Shop\Product\Domain\ValueObject\ProductVariantSize;
use Tests\Src\Shop\Shared\Domain\MotherCreator;

final class ProductVariantSizeMother
{
    public static function make(?int $value = null): ProductVariantSize
    {
        return new ProductVariantSize(
            $value ?? MotherCreator::faker()->randomElement(ProductVariantSize::sizesAvailable()),
        );
    }
}
