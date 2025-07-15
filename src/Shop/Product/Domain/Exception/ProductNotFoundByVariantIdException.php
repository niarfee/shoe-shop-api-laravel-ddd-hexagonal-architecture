<?php

declare(strict_types=1);

namespace Src\Shop\Product\Domain\Exception;

use Src\Shop\Shared\Domain\BaseDomainException;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantId;

final class ProductNotFoundByVariantIdException extends BaseDomainException
{
    public function __construct(ProductVariantId $variantId)
    {
        parent::__construct(
            sprintf('No product found for variant <%s>.', $variantId->value()),
        );
    }
}
