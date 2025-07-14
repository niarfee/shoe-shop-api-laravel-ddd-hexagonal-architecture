<?php

declare(strict_types=1);

namespace Src\Shop\Customer\Infrastructure\Repository;

use Src\Shop\Customer\Domain\Customer;
use Src\Shop\Customer\Domain\CustomerRepositoryInterface;
use Src\Shop\Customer\Domain\Exception\CustomerNotFoundByEmailException;
use Src\Shop\Customer\Domain\Exception\DuplicateCustomerEmailException;
use Src\Shop\Customer\Domain\ValueObject\CustomerEmail;
use Src\Shop\Customer\Infrastructure\Mapper\CustomerMapper;
use Src\Shop\Customer\Infrastructure\Persistence\Eloquent\CustomerEloquentModel;
use Src\Shop\Shared\Domain\ValueObject\CustomerId;

class EloquentCustomerRepository implements CustomerRepositoryInterface
{
    public function __construct(
        private CustomerMapper $customerMapper,
    ) {
    }

    public function save(Customer $customer): void
    {
        $existing = CustomerEloquentModel::find($customer->id()->value());
        $customerEloquent = $this->customerMapper->fromDomainToEloquent($customer, $existing);

        if (!$existing && $this->existsByEmail($customer->email())) {
            throw new DuplicateCustomerEmailException(
                $customer->email(),
            );
        }

        $customerEloquent->save();
    }

    public function findByEmail(CustomerEmail $email): Customer
    {
        $customerEloquent = CustomerEloquentModel::where('email', $email->value())->first();
        if (!$customerEloquent) {
            throw new CustomerNotFoundByEmailException($email);
        }
        return $this->customerMapper->fromEloquentToDomain($customerEloquent);
    }

    public function existsById(CustomerId $id): bool
    {
        return CustomerEloquentModel::where('id', $id->value())->exists();
    }

    private function existsByEmail(CustomerEmail $email): bool
    {
        return CustomerEloquentModel::where('email', $email->value())->exists();
    }
}
