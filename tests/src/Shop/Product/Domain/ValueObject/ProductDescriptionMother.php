<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Product\Domain\ValueObject;

use Src\Shop\Product\Domain\ValueObject\ProductDescription;
use Tests\Src\Shop\Shared\Domain\MotherCreator;

final class ProductDescriptionMother
{
    public static function make(?string $value = null): ProductDescription
    {
        return new ProductDescription($value ?? MotherCreator::faker()->text(100));
    }
}
