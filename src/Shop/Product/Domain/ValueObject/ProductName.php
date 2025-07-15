<?php

declare(strict_types=1);

namespace Src\Shop\Product\Domain\ValueObject;

use Src\Shared\Domain\Exception\InvalidStringLengthException;
use Src\Shared\Domain\ValueObject\StringValueObject;
use Src\Shop\Product\Domain\Exception\InvalidProductNameException;

final class ProductName extends StringValueObject
{
    protected const MIN_LENGTH = 1;
    protected const MAX_LENGTH = 50;

    protected function validate(string $value): void
    {
        try {
            parent::validate($value);
        } catch (InvalidStringLengthException $e) {
            throw new InvalidProductNameException(static::MIN_LENGTH, static::MAX_LENGTH);
        }
    }
}
