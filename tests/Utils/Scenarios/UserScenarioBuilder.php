<?php

declare(strict_types=1);

namespace Tests\Utils\Scenarios;

use Src\Shop\Shared\Domain\ValueObject\CustomerId;
use Src\Shop\User\Domain\User;
use Src\Shop\User\Domain\ValueObject\UserEmail;
use Src\Shop\User\Domain\ValueObject\UserPassword;
use Src\Shop\User\Infrastructure\Persistence\Eloquent\UserEloquentModel;
use Tests\Src\Shop\User\Domain\UserMother;

final class UserScenarioBuilder extends ScenarioBuilder
{
    public static function userMotherMakeAndPersist(
        ?CustomerId $id = null,
        ?CustomerId $customerId = null,
        ?UserEmail $email = null,
        ?UserPassword $password = null,
    ): User {
        $user = UserMother::make(
            id: $id,
            customerId: $customerId,
            email: $email,
            password: $password,
        );

        UserEloquentModel::factory()->create([
            'id' => $user->id()->value(),
            'customer_id' => $user->customerId()->value(),
            'email' => $user->email()->value(),
            'password' => $user->password()->value(),
        ]);

        return $user;
    }
}
