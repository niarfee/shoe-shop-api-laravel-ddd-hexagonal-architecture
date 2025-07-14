<?php

declare(strict_types=1);

namespace Tests\Utils\Scenarios;

use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Sanctum;
use Src\Shop\User\Infrastructure\Mapper\UserMapper;
use Tests\Src\Shop\User\Domain\ValueObject\UserEmailMother;
use Tests\Utils\DTO\CustomerWithUserDTO;

final class LoginScenarioBuilder extends ScenarioBuilder
{
    public static function customerMotherAndUserMotherMakeAndPersistAndLoginSanctum(): CustomerWithUserDTO
    {
        $customerWithUserDTO = self::customerMotherAndUserMotherMakeAndPersist();
        $userEloquent = (new UserMapper())->fromDomainToEloquent($customerWithUserDTO->user);
        Sanctum::actingAs($userEloquent);

        return $customerWithUserDTO;
    }

    public static function customerMotherAndUserMotherMakeAndPersistAndLoginAuth(): CustomerWithUserDTO
    {
        $customerWithUserDTO = self::customerMotherAndUserMotherMakeAndPersist();
        Auth::loginUsingId($customerWithUserDTO->user->id()->value());

        return $customerWithUserDTO;
    }

    private static function customerMotherAndUserMotherMakeAndPersist(): CustomerWithUserDTO
    {
        $customer = CustomerScenarioBuilder::customerMotherMakeAndPersist();
        $user = UserScenarioBuilder::userMotherMakeAndPersist(
            customerId: $customer->id(),
            email: UserEmailMother::make($customer->email()->value()),
        );

        return new CustomerWithUserDTO($customer, $user);
    }
}
