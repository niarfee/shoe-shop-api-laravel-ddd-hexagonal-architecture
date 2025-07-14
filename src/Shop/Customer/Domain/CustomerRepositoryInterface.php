<?php

declare(strict_types=1);

namespace Src\Shop\Customer\Domain;

use Src\Shop\Customer\Domain\ValueObject\CustomerEmail;
use Src\Shop\Shared\Domain\ValueObject\CustomerId;

interface CustomerRepositoryInterface
{
    /**
     * Persists a customer. Throws an exception if the email is already taken.
     *
     * @throws DuplicateCustomerEmailException
     */
    public function save(Customer $customer): void;

    /**
     * Retrieves a customer by email.
     *
     * @throws CustomerNotFoundByEmailException
     */
    public function findByEmail(CustomerEmail $email): Customer;

    /**
     * Checks whether a customer with the given ID exists.
     */
    public function existsById(CustomerId $id): bool;
}
