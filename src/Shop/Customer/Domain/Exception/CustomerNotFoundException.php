<?php

declare(strict_types=1);

namespace Src\Shop\Customer\Domain\Exception;

use Src\Shop\Shared\Domain\BaseDomainException;
use Src\Shop\Shared\Domain\ValueObject\CustomerId;

final class CustomerNotFoundException extends BaseDomainException
{
    public function __construct(CustomerId $customerId)
    {
        parent::__construct(
            sprintf('Customer <%s> not found.', $customerId->value()),
        );
    }
}
