<?php

declare(strict_types=1);

namespace Src\Shop\Product\Domain\Exception;

use Src\Shop\Shared\Domain\BaseDomainException;

final class InvalidProductDescriptionException extends BaseDomainException
{
    public function __construct(int $minLength, int $maxLength)
    {
        parent::__construct(
            sprintf('Invalid product description, the length must be between <%d> and <%d> characters.', $minLength, $maxLength),
        );
    }
}
