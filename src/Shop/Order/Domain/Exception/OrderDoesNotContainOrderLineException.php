<?php

declare(strict_types=1);

namespace Src\Shop\Order\Domain\Exception;

use Src\Shop\Order\Domain\ValueObject\OrderId;
use Src\Shop\Order\Domain\ValueObject\OrderLineId;
use Src\Shop\Shared\Domain\BaseDomainException;

final class OrderDoesNotContainOrderLineException extends BaseDomainException
{
    public function __construct(OrderId $orderId, OrderLineId $orderLineId)
    {
        parent::__construct(
            sprintf(
                'Order <%s> does not contain OrderLine <%s>.',
                $orderId->value(),
                $orderLineId->value(),
            ),
        );
    }
}
