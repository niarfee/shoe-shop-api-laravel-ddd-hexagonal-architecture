<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Exception;

use Src\Shop\Shared\Domain\BaseDomainException;

final class InvalidTokenTypeException extends BaseDomainException
{
    public function __construct(string $invalidTokenType)
    {
        parent::__construct(
            sprintf('Invalid token type <%s>.', $invalidTokenType),
        );
    }
}
