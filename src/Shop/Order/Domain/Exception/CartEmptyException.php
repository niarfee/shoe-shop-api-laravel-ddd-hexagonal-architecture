<?php

declare(strict_types=1);

namespace Src\Shop\Order\Domain\Exception;

use Src\Shop\Order\Domain\ValueObject\OrderId;
use Src\Shop\Shared\Domain\BaseDomainException;

final class CartEmptyException extends BaseDomainException
{
    public function __construct(OrderId $orderId)
    {
        parent::__construct(
            sprintf('Cart <%s> is empty.', $orderId->value()),
        );
    }
}
