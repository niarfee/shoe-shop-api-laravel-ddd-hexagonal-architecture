<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Customer\Domain;

use Src\Shop\Customer\Domain\Customer;
use Src\Shop\Customer\Domain\ValueObject\CustomerEmail;
use Src\Shop\Customer\Domain\ValueObject\CustomerFirstName;
use Src\Shop\Customer\Domain\ValueObject\CustomerLastName;
use Src\Shop\Shared\Domain\ValueObject\CustomerId;
use Tests\Src\Shop\Customer\Domain\ValueObject\CustomerEmailMother;
use Tests\Src\Shop\Customer\Domain\ValueObject\CustomerFirstNameMother;
use Tests\Src\Shop\Customer\Domain\ValueObject\CustomerLastNameMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\CustomerIdMother;

final class CustomerMother
{
    public static function make(
        ?CustomerId $id = null,
        ?CustomerFirstName $firstName = null,
        ?CustomerLastName $lastName = null,
        ?CustomerEmail $email = null,
    ): Customer {
        return Customer::create(
            id: $id ?? CustomerIdMother::make(),
            firstName: $firstName ?? CustomerFirstNameMother::make(),
            lastName: $lastName ?? CustomerLastNameMother::make(),
            email: $email ?? CustomerEmailMother::make(),
        );
    }
}
