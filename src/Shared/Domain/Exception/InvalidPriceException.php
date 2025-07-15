<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Exception;

use Src\Shop\Shared\Domain\BaseDomainException;

final class InvalidPriceException extends BaseDomainException
{
    public function __construct(float $invalidPrice)
    {
        parent::__construct(
            sprintf('The price <%s> must be positive.', number_format($invalidPrice, 2)),
        );
    }
}
