<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Exception;

use Src\Shop\Shared\Domain\BaseDomainException;

final class InvalidPasswordException extends BaseDomainException
{
    public function __construct(int $passwordMinLength, int $passwordMaxLength)
    {
        parent::__construct(
            sprintf(
                'The password must have between <%d> and <%d> characters, a lowercase letter, an uppercase letter, a number and a special character.',
                $passwordMinLength,
                $passwordMaxLength,
            ),
        );
    }
}
