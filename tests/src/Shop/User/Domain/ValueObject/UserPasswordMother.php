<?php

declare(strict_types=1);

namespace Tests\Src\Shop\User\Domain\ValueObject;

use Src\Shop\User\Domain\ValueObject\UserPassword;
use Tests\Src\Shop\Shared\Domain\MotherCreator;

final class UserPasswordMother
{
    public static function make(?string $plain = null, ?string $confirm = null): UserPassword
    {
        $plain = $plain ?? MotherCreator::faker()->regexify('[A-Z][a-z]{3,6}[0-9]{2}[!@#]');
        return new UserPassword($plain, $confirm ?? $plain);
    }
}
