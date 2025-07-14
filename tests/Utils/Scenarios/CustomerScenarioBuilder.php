<?php

declare(strict_types=1);

namespace Tests\Utils\Scenarios;

use Src\Shop\Customer\Domain\Customer;
use Src\Shop\Customer\Domain\ValueObject\CustomerEmail;
use Src\Shop\Customer\Domain\ValueObject\CustomerFirstName;
use Src\Shop\Customer\Domain\ValueObject\CustomerLastName;
use Src\Shop\Customer\Infrastructure\Persistence\Eloquent\CustomerEloquentModel;
use Src\Shop\Shared\Domain\ValueObject\CustomerId;
use Tests\Src\Shop\Customer\Domain\CustomerMother;

final class CustomerScenarioBuilder extends ScenarioBuilder
{
    public static function customerMotherMakeAndPersist(
        ?CustomerId $id = null,
        ?CustomerFirstName $firstName = null,
        ?CustomerLastName $lastName = null,
        ?CustomerEmail $email = null,
    ): Customer {
        $customer = CustomerMother::make(
            id: $id,
            firstName: $firstName,
            lastName: $lastName,
            email: $email,
        );

        CustomerEloquentModel::factory()->create([
            'id' => $customer->id()->value(),
            'first_name' => $customer->firstName()->value(),
            'last_name' => $customer->lastName()->value(),
            'email' => $customer->email()->value(),
        ]);

        return $customer;
    }
}
