<?php

declare(strict_types=1);

namespace Src\Shop\User\Domain\ValueObject;

use Src\Shared\Domain\ValueObject\EmailValueObject;

final class UserEmail extends EmailValueObject
{
    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
