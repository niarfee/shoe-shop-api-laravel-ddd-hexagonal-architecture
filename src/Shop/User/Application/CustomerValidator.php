<?php

declare(strict_types=1);

namespace Src\Shop\User\Application;

use Src\Shop\Customer\Domain\CustomerRepositoryInterface;
use Src\Shop\Customer\Domain\Exception\CustomerNotFoundException;
use Src\Shop\Shared\Domain\ValueObject\CustomerId;

final class CustomerValidator
{
    public function __construct(
        private CustomerRepositoryInterface $customerRepository,
    ) {
    }

    public function validateCustomerExists(CustomerId $customerId): void
    {
        if (!$this->customerRepository->existsById($customerId)) {
            throw new CustomerNotFoundException($customerId);
        }
    }
}
