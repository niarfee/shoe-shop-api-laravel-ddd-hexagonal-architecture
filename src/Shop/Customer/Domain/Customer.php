<?php

declare(strict_types=1);

namespace Src\Shop\Customer\Domain;

use Src\Shop\Customer\Domain\ValueObject\CustomerEmail;
use Src\Shop\Customer\Domain\ValueObject\CustomerFirstName;
use Src\Shop\Customer\Domain\ValueObject\CustomerLastName;
use Src\Shop\Shared\Domain\ValueObject\CustomerId;

final class Customer
{
    private function __construct(
        private readonly CustomerId $id,
        private readonly CustomerFirstName $firstName,
        private readonly CustomerLastName $lastName,
        private readonly CustomerEmail $email,
    ) {
    }

    public static function create(CustomerId $id, CustomerFirstName $firstName, CustomerLastName $lastName, CustomerEmail $email): self
    {
        $customer = new self($id, $firstName, $lastName, $email);
        return $customer;
    }

    // Public accessors

    public function id(): CustomerId
    {
        return $this->id;
    }

    public function firstName(): CustomerFirstName
    {
        return $this->firstName;
    }

    public function lastName(): CustomerLastName
    {
        return $this->lastName;
    }

    public function email(): CustomerEmail
    {
        return $this->email;
    }
}
