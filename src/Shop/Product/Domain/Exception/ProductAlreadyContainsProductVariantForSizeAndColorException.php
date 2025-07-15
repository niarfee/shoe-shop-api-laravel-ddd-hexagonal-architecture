<?php

declare(strict_types=1);

namespace Src\Shop\Product\Domain\Exception;

use Src\Shop\Product\Domain\ValueObject\ProductId;
use Src\Shop\Product\Domain\ValueObject\ProductVariantColor;
use Src\Shop\Product\Domain\ValueObject\ProductVariantSize;
use Src\Shop\Shared\Domain\BaseDomainException;

final class ProductAlreadyContainsProductVariantForSizeAndColorException extends BaseDomainException
{
    public function __construct(
        ProductId $productId,
        ProductVariantSize $productVariantSize,
        ProductVariantColor $productVariantColor,
    ) {
        parent::__construct(
            sprintf(
                'Product <%s> already contains a variant for size <%s> and color <%s>.',
                $productId->value(),
                $productVariantSize->value(),
                $productVariantColor->value(),
            ),
        );
    }
}
