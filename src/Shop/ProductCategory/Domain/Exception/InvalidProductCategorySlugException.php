<?php

declare(strict_types=1);

namespace Src\Shop\ProductCategory\Domain\Exception;

use Src\Shop\Shared\Domain\BaseDomainException;

final class InvalidProductCategorySlugException extends BaseDomainException
{
    public function __construct(string $invalidProductCategorySlug)
    {
        parent::__construct(
            sprintf(
                'Invalid product category slug, please use only letters, numbers and hyphens. Got <%s>.',
                $invalidProductCategorySlug,
            ),
        );
    }
}
