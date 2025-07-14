<?php

declare(strict_types=1);

namespace Src\Shop\ProductCategory\Domain\Exception;

use Src\Shop\Shared\Domain\BaseDomainException;
use Src\Shop\Shared\Domain\ValueObject\ProductCategoryId;

final class ProductCategoryNotFoundException extends BaseDomainException
{
    public function __construct(ProductCategoryId $productCategoryId)
    {
        parent::__construct(
            sprintf('Product category <%s> not found.', $productCategoryId->value()),
        );
    }
}
