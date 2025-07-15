<?php

declare(strict_types=1);

namespace Src\Shop\Shared\Domain\Exception;

use Src\Shop\Shared\Domain\BaseDomainException;

final class ProductVariantStockNegativeException extends BaseDomainException
{
    public function __construct(int $invalidValue)
    {
        parent::__construct(
            sprintf('Product variant stock <%d> must be positive.', $invalidValue),
        );
    }
}
