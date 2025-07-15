<?php

declare(strict_types=1);

namespace Src\Shop\Product\Domain\Exception;

use Src\Shop\Shared\Domain\BaseDomainException;

final class InvalidProductVariantColorException extends BaseDomainException
{
    public function __construct(array $validColors)
    {
        parent::__construct(
            sprintf(
                'Invalid product variant color, please use only: <%s>.',
                implode(', ', $validColors),
            ),
        );
    }
}
