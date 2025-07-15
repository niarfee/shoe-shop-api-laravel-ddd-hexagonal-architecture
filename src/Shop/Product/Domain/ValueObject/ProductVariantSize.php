<?php

declare(strict_types=1);

namespace Src\Shop\Product\Domain\ValueObject;

use Src\Shared\Domain\ValueObject\IntUnsignedValueObject;
use Src\Shop\Product\Domain\Exception\InvalidProductVariantSizeException;

final class ProductVariantSize extends IntUnsignedValueObject
{
    private const SIZES_AVAILABLE = [34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45];

    protected readonly int $value;

    public function __construct(int $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    public static function sizesAvailable(): array
    {
        return self::SIZES_AVAILABLE;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    private function validate(int $value): void
    {
        if (!in_array($value, self::SIZES_AVAILABLE)) {
            throw new InvalidProductVariantSizeException(
                self::sizesAvailable(),
            );
        }
    }
}
