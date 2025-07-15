<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Shared\Domain\ValueObject;

use Src\Shop\Shared\Domain\ValueObject\ProductVariantStockRequested;
use Tests\Src\Shop\Shared\Domain\MotherCreator;

final class ProductVariantStockRequestedMother
{
    public static function make(?int $value = null): ProductVariantStockRequested
    {
        return new ProductVariantStockRequested($value ?? MotherCreator::faker()->numberBetween(0, 100));
    }
}
