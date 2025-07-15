<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Product\Domain\ValueObject;

use Src\Shop\Product\Domain\ValueObject\ProductName;
use Tests\Src\Shop\Shared\Domain\MotherCreator;

final class ProductNameMother
{
    public static function make(?string $value = null): ProductName
    {
        return new ProductName($value ?? MotherCreator::faker()->text(20));
    }
}
