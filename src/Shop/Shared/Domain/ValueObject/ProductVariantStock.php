<?php

declare(strict_types=1);

namespace Src\Shop\Shared\Domain\ValueObject;

use InvalidArgumentException;
use Src\Shared\Domain\ValueObject\IntUnsignedValueObject;
use Src\Shop\Shared\Domain\Exception\ProductVariantStockNegativeException;

final class ProductVariantStock extends IntUnsignedValueObject
{
    public function __construct(int $value)
    {
        try {
            parent::__construct($value);
        } catch (InvalidArgumentException $e) {
            throw new ProductVariantStockNegativeException($value);
        }
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
