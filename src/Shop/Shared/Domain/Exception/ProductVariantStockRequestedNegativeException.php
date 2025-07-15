<?php

declare(strict_types=1);

namespace Src\Shop\Shared\Domain\Exception;

use Src\Shop\Shared\Domain\BaseDomainException;

final class ProductVariantStockRequestedNegativeException extends BaseDomainException
{
    public function __construct(int $invalidValue)
    {
        parent::__construct(
            sprintf('Product variant stock quantity <%d> must be positive.', $invalidValue),
        );
    }
}
