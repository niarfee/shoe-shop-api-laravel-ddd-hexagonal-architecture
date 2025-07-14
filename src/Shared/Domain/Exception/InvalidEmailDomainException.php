<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Exception;

use Src\Shop\Shared\Domain\BaseDomainException;

final class InvalidEmailDomainException extends BaseDomainException
{
    public function __construct(string $invalidDomain)
    {
        parent::__construct(
            sprintf('The email domain <%s> does not exist or is not properly configured.', $invalidDomain),
        );
    }
}
