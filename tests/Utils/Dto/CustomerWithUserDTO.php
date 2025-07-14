<?php

declare(strict_types=1);

namespace Tests\Utils\DTO;

use Src\Shop\Customer\Domain\Customer;
use Src\Shop\User\Domain\User;

final class CustomerWithUserDTO
{
    public function __construct(
        public readonly Customer $customer,
        public readonly User $user,
    ) {
    }
}
