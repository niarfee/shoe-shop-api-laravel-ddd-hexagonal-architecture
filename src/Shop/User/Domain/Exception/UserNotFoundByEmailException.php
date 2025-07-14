<?php

declare(strict_types=1);

namespace Src\Shop\User\Domain\Exception;

use Src\Shop\Shared\Domain\BaseDomainException;
use Src\Shop\User\Domain\ValueObject\UserEmail;

final class UserNotFoundByEmailException extends BaseDomainException
{
    public function __construct(UserEmail $userEmail)
    {
        parent::__construct(
            sprintf('No user found with email <%s>.', $userEmail->value()),
        );
    }
}
