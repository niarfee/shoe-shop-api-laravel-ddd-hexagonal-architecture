<?php

declare(strict_types=1);

namespace Src\Shop\User\Domain\ValueObject;

use Src\Shared\Domain\Exception\InvalidTokenTypeException;

final class TokenType
{
    protected readonly string $value;

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    final public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    private function validate(string $value): void
    {
        if (!in_array($value, ['Bearer'])) {
            throw new InvalidTokenTypeException($value);
        }
    }
}
