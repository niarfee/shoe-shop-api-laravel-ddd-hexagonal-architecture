<?php

declare(strict_types=1);

namespace Src\Shop\Product\Domain\Exception;

use Src\Shop\Product\Domain\ValueObject\ProductVariantSize;
use Src\Shop\Shared\Domain\BaseDomainException;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantId;

final class ProductVariantSizeDoesNotMatchException extends BaseDomainException
{
    public function __construct(
        ProductVariantId $productVariantId,
        ProductVariantSize $expectedSize,
        ProductVariantSize $actualSize,
    ) {
        parent::__construct(
            sprintf(
                'Size mismatch for ProductVariantId <%s>: expected <%s>, got <%s>.',
                $productVariantId->value(),
                $expectedSize->value(),
                $actualSize->value(),
            ),
        );
    }
}
