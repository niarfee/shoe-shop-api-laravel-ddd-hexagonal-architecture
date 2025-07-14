<?php

declare(strict_types=1);

namespace Src\Shop\Customer\Domain\ValueObject;

use Src\Shared\Domain\ValueObject\EmailValueObject;

final class CustomerEmail extends EmailValueObject
{
    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
