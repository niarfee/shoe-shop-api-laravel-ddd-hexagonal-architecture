<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Product\Domain\ValueObject;

use Src\Shop\Product\Domain\ValueObject\ProductPrice;
use Tests\Src\Shop\Shared\Domain\MotherCreator;

final class ProductPriceMother
{
    public static function make(?float $value = null): ProductPrice
    {
        return new ProductPrice($value ?? MotherCreator::faker()->randomFloat(2, 1, 50));
    }
}
