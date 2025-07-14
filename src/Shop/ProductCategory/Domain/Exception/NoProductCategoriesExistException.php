<?php

declare(strict_types=1);

namespace Src\Shop\ProductCategory\Domain\Exception;

use Src\Shop\Shared\Domain\BaseDomainException;

final class NoProductCategoriesExistException extends BaseDomainException
{
    public function __construct()
    {
        parent::__construct(
            sprintf('No categories found.'),
        );
    }
}
