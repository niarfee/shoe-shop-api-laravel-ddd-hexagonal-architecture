<?php

declare(strict_types=1);

namespace Src\Shop\Order\Domain\Exception;

use Src\Shop\Order\Domain\ValueObject\OrderStatus;
use Src\Shop\Shared\Domain\BaseDomainException;

final class OrderNotInCartException extends BaseDomainException
{
    public function __construct(OrderStatus $orderStatus)
    {
        parent::__construct(
            sprintf("The Order is not 'In Cart' OrderStatus and its OrderLines cannot be modified. Current OrderStatus: <%s>.", $orderStatus->label()),
        );
    }
}
