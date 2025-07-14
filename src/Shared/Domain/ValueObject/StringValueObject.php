<?php

declare(strict_types=1);

namespace Src\Shared\Domain\ValueObject;

use Src\Shared\Domain\Exception\InvalidStringLengthException;

abstract class StringValueObject
{
    protected const MIN_LENGTH = 1;
    protected const MAX_LENGTH = 255;

    protected readonly string $value;

    public function __construct(string $value)
    {
        $this->value = trim($value);
        $this->validate($this->value);
    }

    final public function value(): string
    {
        return $this->value;
    }

    protected function validate(string $value): void
    {
        $length = strlen($value);
        $minLength = static::MIN_LENGTH;
        $maxLength = static::MAX_LENGTH;

        if ($length < $minLength || $length > $maxLength) {
            throw new InvalidStringLengthException($minLength, $maxLength);
        }
    }
}
