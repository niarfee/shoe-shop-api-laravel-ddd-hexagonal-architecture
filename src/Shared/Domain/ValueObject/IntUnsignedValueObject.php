<?php

declare(strict_types=1);

namespace Src\Shared\Domain\ValueObject;

use InvalidArgumentException;

abstract class IntUnsignedValueObject
{
    protected readonly int $value;

    public function __construct(int $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    final public function value(): int
    {
        return $this->value;
    }

    private function validate(int $value): void
    {
        $options = [
            'options' => [
                'min_range' => 0,
            ],
        ];

        if (filter_var($value, FILTER_VALIDATE_INT, $options) === false) {
            throw new InvalidArgumentException(
                sprintf('<%s> does not allow the value %s.', static::class, $value),
            );
        }
    }
}
