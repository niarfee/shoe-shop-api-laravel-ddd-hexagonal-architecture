<?php

declare(strict_types=1);

namespace Src\Shop\Customer\Domain\Exception;

use Src\Shop\Customer\Domain\ValueObject\CustomerEmail;
use Src\Shop\Shared\Domain\BaseDomainException;

final class DuplicateCustomerEmailException extends BaseDomainException
{
    public function __construct(CustomerEmail $customerEmail)
    {
        parent::__construct(
            sprintf('Customer email <%s> not available.', $customerEmail->value()),
        );
    }
}
