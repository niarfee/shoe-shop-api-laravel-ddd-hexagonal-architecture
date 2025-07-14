<?php

declare(strict_types=1);

namespace Src\Shop\Customer\Application\Response\Mapper;

use Src\Shop\Customer\Application\Response\Dto\CustomerResponseDTO;
use Src\Shop\Customer\Domain\Customer;

final class CustomerResponseMapper
{
    public function map(Customer $customer): CustomerResponseDTO
    {
        return new CustomerResponseDTO(
            id: $customer->id()->value(),
            firstName: $customer->firstName()->value(),
            lastName: $customer->lastName()->value(),
            email: $customer->email()->value(),
        );
    }
}
