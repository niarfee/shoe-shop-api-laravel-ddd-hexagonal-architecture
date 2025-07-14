<?php

declare(strict_types=1);

namespace Src\Shop\Customer\Domain\ValueObject;

use Src\Shared\Domain\ValueObject\StringValueObject;

final class CustomerLastName extends StringValueObject
{
    protected const MIN_LENGTH = 2;
    protected const MAX_LENGTH = 100;

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
