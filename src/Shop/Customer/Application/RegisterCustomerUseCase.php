<?php

declare(strict_types=1);

namespace Src\Shop\Customer\Application;

use Src\Shop\Customer\Application\Response\Dto\CustomerResponseDTO;
use Src\Shop\Customer\Application\Response\Mapper\CustomerResponseMapper;
use Src\Shop\Customer\Domain\Customer;
use Src\Shop\Customer\Domain\CustomerRepositoryInterface;
use Src\Shop\Customer\Domain\ValueObject\CustomerEmail;
use Src\Shop\Customer\Domain\ValueObject\CustomerFirstName;
use Src\Shop\Customer\Domain\ValueObject\CustomerLastName;
use Src\Shop\Shared\Domain\ValueObject\CustomerId;

final class RegisterCustomerUseCase
{
    public function __construct(
        private CustomerRepositoryInterface $customerRepository,
        private CustomerResponseMapper $customerResponseMapper,
    ) {
    }

    public function __invoke(
        string $firstName,
        string $lastName,
        string $email,
    ): CustomerResponseDTO {
        $customerEmail =  new CustomerEmail($email);
        $customerToCreate = Customer::create(
            id: CustomerId::generate(),
            firstName: new CustomerFirstName($firstName),
            lastName: new CustomerLastName($lastName),
            email: $customerEmail,
        );
        $this->customerRepository->save($customerToCreate);

        return $this->customerResponseMapper->map($customerToCreate);
    }
}
