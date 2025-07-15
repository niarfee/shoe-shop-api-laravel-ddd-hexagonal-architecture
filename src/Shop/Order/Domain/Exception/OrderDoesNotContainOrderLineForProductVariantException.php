<?php

declare(strict_types=1);

namespace Src\Shop\Order\Domain\Exception;

use Src\Shop\Order\Domain\ValueObject\OrderId;
use Src\Shop\Shared\Domain\BaseDomainException;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantId;

final class OrderDoesNotContainOrderLineForProductVariantException extends BaseDomainException
{
    public function __construct(OrderId $orderId, ProductVariantId $productVariantId)
    {
        parent::__construct(
            sprintf(
                'Order <%s> does not contain an OrderLine for ProductVariant <%s>.',
                $orderId->value(),
                $productVariantId->value(),
            ),
        );
    }
}
