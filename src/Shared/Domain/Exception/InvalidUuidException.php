<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Exception;

use Src\Shop\Shared\Domain\BaseDomainException;

final class InvalidUuidException extends BaseDomainException
{
    public function __construct(
        string $className,
        string $id,
    ) {
        $entity = self::humanizeClassName($className);

        parent::__construct(
            sprintf('<%s> does not allow the value uuid <%s>.', $entity, $id),
        );
    }
}
