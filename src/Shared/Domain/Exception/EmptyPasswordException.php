<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Exception;

use Src\Shop\Shared\Domain\BaseDomainException;

final class EmptyPasswordException extends BaseDomainException
{
    public function __construct()
    {
        parent::__construct('The password cannot be empty.');
    }
}
