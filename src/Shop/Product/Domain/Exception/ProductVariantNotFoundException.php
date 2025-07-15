<?php

declare(strict_types=1);

namespace Src\Shop\Product\Domain\Exception;

use Src\Shop\Shared\Domain\BaseDomainException;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantId;

final class ProductVariantNotFoundException extends BaseDomainException
{
    public function __construct(ProductVariantId $productVariantId)
    {
        parent::__construct(
            sprintf('Product variant <%s> not found.', $productVariantId->value()),
        );
    }
}
