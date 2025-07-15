<?php

declare(strict_types=1);

namespace Src\Shop\Product\Domain\Exception;

use Src\Shop\Product\Domain\ValueObject\ProductVariantColor;
use Src\Shop\Shared\Domain\BaseDomainException;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantId;

final class ProductVariantColorDoesNotMatchException extends BaseDomainException
{
    public function __construct(
        ProductVariantId $productVariantId,
        ProductVariantColor $expectedColor,
        ProductVariantColor $actualColor,
    ) {
        parent::__construct(
            sprintf(
                'Color mismatch for ProductVariantId <%s>: expected <%s>, got <%s>.',
                $productVariantId->value(),
                $expectedColor->value(),
                $actualColor->value(),
            ),
        );
    }
}
