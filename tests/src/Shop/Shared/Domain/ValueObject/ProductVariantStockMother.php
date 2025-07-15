<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Shared\Domain\ValueObject;

use Src\Shop\Shared\Domain\ValueObject\ProductVariantStock;
use Tests\Src\Shop\Shared\Domain\MotherCreator;

final class ProductVariantStockMother
{
    public static function make(?int $value = null): ProductVariantStock
    {
        return new ProductVariantStock($value ?? MotherCreator::faker()->numberBetween(0, 100));
    }
}
