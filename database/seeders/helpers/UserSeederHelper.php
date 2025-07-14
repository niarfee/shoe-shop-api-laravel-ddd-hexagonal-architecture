<?php

declare(strict_types=1);

namespace Database\Seeders\Helpers;

use Src\Shop\User\Infrastructure\Persistence\Eloquent\UserEloquentModel;
use Tests\Src\Shop\Shared\Domain\ValueObject\CustomerIdMother;
use Tests\Src\Shop\User\Domain\UserMother;
use Tests\Src\Shop\User\Domain\ValueObject\UserEmailMother;

class UserSeederHelper extends SeederHelper
{
    public function buildFake(array $customers): array
    {
        // return UserEloquentModel::factory()
        //     ->count(count($customers))
        //     ->sequence(...array_map(function ($customer) {
        //         return [
        //             'customer_id' => $customer['id'],
        //             'email' => $customer['email'],
        //         ];
        //     }, $customers))
        //     ->make()
        //     ->makeVisible(['password', 'remember_token'])
        //     ->toArray();

        $users = [];

        foreach ($customers as $customer) {
            $users[] = UserMother::make(
                customerId: CustomerIdMother::make($customer->id()->value()),
                email: UserEmailMother::make($customer->email()->value()),
            );
        }

        return $users;
    }

    public function fromDomainListToArrayList(array $users): array
    {
        $usersArray = [];

        foreach ($users as $user) {
            $usersArray[] = [
                'id' => $user->id()->value(),
                'customer_id' => $user->customerId()->value(),
                'email' => $user->email()->value(),
                'email_verified_at' => now(),
                'password' => '$2y$12$iKeB5PCGyky5zoUcIxh9oeGVQQp096zbiclPAt2QVPRwNSVYIItNe', // password
                // 'remember_token' => '',
            ];
        }

        return $usersArray;
    }

    public function persistList(array $users): void
    {
        parent::persistListBase(UserEloquentModel::class, $users);
    }
}
