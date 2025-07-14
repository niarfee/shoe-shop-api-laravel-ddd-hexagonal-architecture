<?php

declare(strict_types=1);

namespace Tests\Src\Shop\User\Domain;

use Src\Shop\Shared\Domain\ValueObject\CustomerId;
use Src\Shop\User\Domain\User;
use Src\Shop\User\Domain\ValueObject\UserEmail;
use Src\Shop\User\Domain\ValueObject\UserId;
use Src\Shop\User\Domain\ValueObject\UserPassword;
use Tests\Src\Shop\Shared\Domain\ValueObject\CustomerIdMother;
use Tests\Src\Shop\User\Domain\ValueObject\UserEmailMother;
use Tests\Src\Shop\User\Domain\ValueObject\UserIdMother;
use Tests\Src\Shop\User\Domain\ValueObject\UserPasswordMother;

final class UserMother
{
    public static function make(
        ?UserId $id = null,
        ?CustomerId $customerId = null,
        ?UserEmail $email = null,
        ?UserPassword $password = null,
    ): User {
        return User::create(
            id: $id ?? UserIdMother::make(),
            customerId: $customerId ?? CustomerIdMother::make(),
            email: $email ?? UserEmailMother::make(),
            password: $password ?? UserPasswordMother::make(),
        );
    }
}
