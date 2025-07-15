<?php

declare(strict_types=1);

namespace Src\Shop\Order\Domain\Exception;

use Src\Shop\Shared\Domain\BaseDomainException;

final class InvalidOrderStatusException extends BaseDomainException
{
    public function __construct(int $orderStatus)
    {
        parent::__construct(
            sprintf('Order status <%d> invalid.', $orderStatus),
        );
    }
}
