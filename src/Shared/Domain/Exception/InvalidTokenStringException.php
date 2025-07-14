<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Exception;

use Src\Shop\Shared\Domain\BaseDomainException;

final class InvalidTokenStringException extends BaseDomainException
{
    public function __construct(string $invalidTokenString)
    {
        parent::__construct(
            sprintf('Invalid token <%s>.', $invalidTokenString),
        );
    }
}
