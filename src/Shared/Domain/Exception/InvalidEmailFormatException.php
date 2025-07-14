<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Exception;

use Src\Shop\Shared\Domain\BaseDomainException;

final class InvalidEmailFormatException extends BaseDomainException
{
    public function __construct(string $invalidEmail)
    {
        parent::__construct(
            sprintf('The email format <%s> is not valid.', $invalidEmail),
        );
    }
}
