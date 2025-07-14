<?php

declare(strict_types=1);

namespace Src\Shop\User\Infrastructure\Mapper;

use Illuminate\Support\Str;
use Src\Shop\Shared\Domain\ValueObject\CustomerId;
use Src\Shop\User\Domain\User;
use Src\Shop\User\Domain\ValueObject\UserEmail;
use Src\Shop\User\Domain\ValueObject\UserId;
use Src\Shop\User\Domain\ValueObject\UserPassword;
use Src\Shop\User\Infrastructure\Persistence\Eloquent\UserEloquentModel;

final class UserMapper
{
    public function fromEloquentToDomain(UserEloquentModel $userEloquentModel): User
    {
        return User::create(
            id: new UserId($userEloquentModel->id),
            customerId: new CustomerId($userEloquentModel->customer_id),
            email: new UserEmail($userEloquentModel->email),
            password: new UserPassword($userEloquentModel->password),
        );
    }

    public function fromDomainToEloquent(
        User $user,
        ?UserEloquentModel $userEloquent = null,
    ): UserEloquentModel {
        $userEloquent ??= new UserEloquentModel();

        $userEloquent->id = $user->id()->value();
        $userEloquent->customer_id = $user->customerId()->value();
        $userEloquent->email = $user->email()->value();
        // $userEloquent->email_verified_at = now();
        $userEloquent->password = $user->password()->value();
        $userEloquent->remember_token = Str::random(10);

        return $userEloquent;
    }
}
