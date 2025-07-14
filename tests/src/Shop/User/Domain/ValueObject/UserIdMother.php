<?php

declare(strict_types=1);

namespace Tests\Src\Shop\User\Domain\ValueObject;

use Src\Shop\User\Domain\ValueObject\UserId;

final class UserIdMother
{
    public static function make(?string $value = null): UserId
    {
        return new UserId($value ?? UserId::generate()->value());
    }
}
