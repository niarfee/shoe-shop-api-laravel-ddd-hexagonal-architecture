<?php

declare(strict_types=1);

namespace Src\Shop\Order\Domain\Exception;

use Src\Shop\Shared\Domain\BaseDomainException;
use Src\Shop\Shared\Domain\ValueObject\CustomerId;

final class CartNotFoundException extends BaseDomainException
{
    public function __construct(CustomerId $customerId)
    {
        parent::__construct(
            sprintf('Cart not found for customer <%s>.', $customerId->value()),
        );
    }
}
