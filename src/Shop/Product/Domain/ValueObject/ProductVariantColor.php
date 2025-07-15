<?php

declare(strict_types=1);

namespace Src\Shop\Product\Domain\ValueObject;

use Src\Shared\Domain\ValueObject\StringValueObject;
use Src\Shop\Product\Domain\Enum\ProductVariantColorEnum;
use Src\Shop\Product\Domain\Exception\InvalidProductVariantColorException;

final class ProductVariantColor extends StringValueObject
{
    public static function create(string $value): self
    {
        return new self($value);
    }

    public static function createGreen(): self
    {
        return self::create(ProductVariantColorEnum::Green->value);
    }

    public static function createRed(): self
    {
        return self::create(ProductVariantColorEnum::Red->value);
    }

    public static function createWhite(): self
    {
        return self::create(ProductVariantColorEnum::White->value);
    }

    public static function createBlack(): self
    {
        return self::create(ProductVariantColorEnum::Black->value);
    }

    public static function createBlue(): self
    {
        return self::create(ProductVariantColorEnum::Blue->value);
    }

    public static function createOcre(): self
    {
        return self::create(ProductVariantColorEnum::Ocre->value);
    }

    public static function colorNamesAvailable(): array
    {
        return array_map(
            fn (ProductVariantColorEnum $color) => $color->value,
            ProductVariantColorEnum::cases(),
        );
    }

    public function hexCode(): string
    {
        return ProductVariantColorEnum::from($this->value)->hexCode();
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    protected function validate(string $value): void
    {
        if (!ProductVariantColorEnum::tryFrom($value)) {
            throw new InvalidProductVariantColorException(
                self::colorNamesAvailable(),
            );
        }
    }
}
