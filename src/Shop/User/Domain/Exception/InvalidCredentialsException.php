<?php

declare(strict_types=1);

namespace Src\Shop\User\Domain\Exception;

use Src\Shop\Shared\Domain\BaseDomainException;

final class InvalidCredentialsException extends BaseDomainException
{
    public function __construct()
    {
        parent::__construct('Invalid credentials provided. Please check your email and password.');
    }
}
