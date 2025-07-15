<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Product\Domain\ValueObject;

use Src\Shop\Product\Domain\ValueObject\ProductVariantColor;
use Tests\Src\Shop\Shared\Domain\MotherCreator;

final class ProductVariantColorMother
{
    public static function make(?string $value = null): ProductVariantColor
    {
        return new ProductVariantColor(
            $value ?? MotherCreator::faker()->randomElement(ProductVariantColor::colorNamesAvailable()),
        );
    }
}
