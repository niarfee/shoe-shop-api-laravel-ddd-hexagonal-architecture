<?php

declare(strict_types=1);

namespace Src\Shop\Product\Domain\Exception;

use Src\Shop\Product\Domain\ValueObject\ProductId;
use Src\Shop\Shared\Domain\BaseDomainException;

final class ProductNotFoundException extends BaseDomainException
{
    public function __construct(ProductId $productId)
    {
        parent::__construct(
            sprintf('The product <%s> has not been found.', $productId->value()),
        );
    }
}
