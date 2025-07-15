<?php

declare(strict_types=1);

namespace Src\Shop\Order\Domain\Exception;

use Src\Shop\Order\Domain\ValueObject\OrderLineId;
use Src\Shop\Shared\Domain\BaseDomainException;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantId;

final class OrderLineVariantIdDoesNotMatchException extends BaseDomainException
{
    public function __construct(
        OrderLineId $orderLineId,
        ProductVariantId $expectedProductVariantId,
        ProductVariantId $actualProductVariantId,
    ) {
        parent::__construct(
            sprintf(
                'ProductVariantId mismatch for OrderLineId <%s>: expected <%s>, got <%s>.',
                $orderLineId->value(),
                $expectedProductVariantId->value(),
                $actualProductVariantId->value(),
            ),
        );
    }
}
