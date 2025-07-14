<?php

declare(strict_types=1);

namespace Src\Shop\ProductCategory\Domain\ValueObject;

use Src\Shared\Domain\ValueObject\StringValueObject;

final class ProductCategoryName extends StringValueObject
{
    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
