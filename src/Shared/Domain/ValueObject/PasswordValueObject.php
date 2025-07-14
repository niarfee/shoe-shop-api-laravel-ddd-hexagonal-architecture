<?php

declare(strict_types=1);

namespace Src\Shared\Domain\ValueObject;

use Src\Shared\Domain\Exception\EmptyPasswordException;

abstract class PasswordValueObject
{
    final public function __construct(protected readonly string $value)
    {
        $this->validate($value);
    }

    final public function value(): string
    {
        return $this->value;
    }

    final public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    private function validate(string $value): void
    {
        if (trim($value) === '') {
            throw new EmptyPasswordException();
        }
    }
}
