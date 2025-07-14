<?php

declare(strict_types=1);

namespace Src\Shop\Customer\Infrastructure\Mapper;

use Src\Shop\Customer\Domain\Customer;
use Src\Shop\Customer\Domain\ValueObject\CustomerEmail;
use Src\Shop\Customer\Domain\ValueObject\CustomerFirstName;
use Src\Shop\Customer\Domain\ValueObject\CustomerLastName;
use Src\Shop\Customer\Infrastructure\Persistence\Eloquent\CustomerEloquentModel;
use Src\Shop\Shared\Domain\ValueObject\CustomerId;

final class CustomerMapper
{
    public function fromEloquentToDomain(CustomerEloquentModel $customerEloquent): Customer
    {
        return Customer::create(
            id: new CustomerId($customerEloquent->id),
            firstName: new CustomerFirstName($customerEloquent->first_name),
            lastName: new CustomerLastName($customerEloquent->last_name),
            email: new CustomerEmail($customerEloquent->email),
        );
    }

    public function fromDomainToEloquent(
        Customer $customer,
        ?CustomerEloquentModel $customerEloquent = null,
    ): CustomerEloquentModel {
        $customerEloquent ??= new CustomerEloquentModel();

        $customerEloquent->id = $customer->id()->value();
        $customerEloquent->first_name = $customer->firstName()->value();
        $customerEloquent->last_name = $customer->lastName()->value();
        $customerEloquent->email = $customer->email()->value();

        return $customerEloquent;
    }
}
