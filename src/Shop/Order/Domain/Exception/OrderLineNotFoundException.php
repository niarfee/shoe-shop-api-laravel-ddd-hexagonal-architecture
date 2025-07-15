<?php

declare(strict_types=1);

namespace Src\Shop\Order\Domain\Exception;

use Src\Shop\Order\Domain\ValueObject\OrderLineId;
use Src\Shop\Shared\Domain\BaseDomainException;

final class OrderLineNotFoundException extends BaseDomainException
{
    public function __construct(OrderLineId $orderLineId)
    {
        parent::__construct(
            sprintf('OrderLine <%s> not found.', $orderLineId->value()),
        );
    }
}
