<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Exception;

use Src\Shop\Shared\Domain\BaseDomainException;

final class InvalidStringLengthException extends BaseDomainException
{
    public function __construct(
        int $minLength,
        int $maxLength,
    ) {
        parent::__construct(
            sprintf('The length must be between <%d> and <%d> characters.', $minLength, $maxLength),
        );
    }
}
