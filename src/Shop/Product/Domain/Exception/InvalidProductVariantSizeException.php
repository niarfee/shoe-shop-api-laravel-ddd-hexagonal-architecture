<?php

declare(strict_types=1);

namespace Src\Shop\Product\Domain\Exception;

use Src\Shop\Shared\Domain\BaseDomainException;

final class InvalidProductVariantSizeException extends BaseDomainException
{
    public function __construct(array $validSizes)
    {
        parent::__construct(
            sprintf(
                'Invalid product variant size, please use only: <%s>.',
                implode(', ', $validSizes),
            ),
        );
    }
}
