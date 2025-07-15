<?php

declare(strict_types=1);

namespace Src\Shop\Order\Domain\Exception;

use Src\Shop\Order\Domain\ValueObject\OrderId;
use Src\Shop\Order\Domain\ValueObject\OrderLineId;
use Src\Shop\Shared\Domain\BaseDomainException;

final class OrderLineDoesNotBelongToOrderException extends BaseDomainException
{
    public function __construct(
        OrderLineId $orderLineId,
        OrderId $lineOrderId,
        OrderId $orderId,
    ) {
        parent::__construct(
            sprintf(
                'OrderLine <%s> with OrderId <%s> does not belong to Order with OrderId <%s>.',
                $orderLineId->value(),
                $lineOrderId->value(),
                $orderId->value(),
            ),
        );
    }
}
