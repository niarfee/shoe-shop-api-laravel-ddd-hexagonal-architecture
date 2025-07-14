<?php

declare(strict_types=1);

namespace Tests\Src\Shop\User\Domain\ValueObject;

use Src\Shop\User\Domain\ValueObject\UserEmail;
use Tests\Src\Shop\Shared\Domain\MotherCreator;

final class UserEmailMother
{
    public static function make(?string $value = null): UserEmail
    {
        return new UserEmail($value ?? MotherCreator::faker()->unique()->safeEmail);
    }
}
