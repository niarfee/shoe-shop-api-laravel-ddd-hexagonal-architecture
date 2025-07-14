<?php

declare(strict_types=1);

namespace Src\Shop\User\Domain\Exception;

use Src\Shop\Shared\Domain\BaseDomainException;
use Src\Shop\User\Domain\ValueObject\UserEmail;

final class DuplicateUserEmailException extends BaseDomainException
{
    public function __construct(UserEmail $userEmail)
    {
        parent::__construct(
            sprintf('User email <%s> not available.', $userEmail->value()),
        );
    }
}
