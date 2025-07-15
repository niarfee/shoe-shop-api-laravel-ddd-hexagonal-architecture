<?php

declare(strict_types=1);

namespace Src\Shared\Domain\ValueObject;

use Src\Shared\Domain\Exception\InvalidPriceException;

abstract class PriceValueObject
{
    protected readonly float $value;

    public function __construct(float $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    final public static function zero(): static
    {
        return new static(0.0);
    }

    final public function value(): float
    {
        return $this->value;
    }

    final public function valueWithSymbol(string $symbol = '€'): string
    {
        return $this->value . ' ' . $symbol;
    }

    final public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    private function validate(float $value): void
    {
        if ($value < 0) {
            throw new InvalidPriceException($value);
        }
    }
}
