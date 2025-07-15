<?php

declare(strict_types=1);

namespace Src\Shop\Product\Domain\Exception;

use Src\Shop\Product\Domain\ValueObject\ProductId;
use Src\Shop\Shared\Domain\BaseDomainException;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantId;

final class ProductVariantDoesNotBelongToProductException extends BaseDomainException
{
    public function __construct(
        ProductVariantId $productVariantId,
        ProductId $variantProductId,
        ProductId $productId,
    ) {
        parent::__construct(
            sprintf(
                'OrderLine <%s> with OrderId <%s> does not belong to Order with OrderId <%s>.',
                $productVariantId->value(),
                $variantProductId->value(),
                $productId->value(),
            ),
        );
    }
}
