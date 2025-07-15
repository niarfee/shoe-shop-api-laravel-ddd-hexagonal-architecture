<?php

declare(strict_types=1);

namespace Src\Shop\Shared\Domain\ValueObject;

use InvalidArgumentException;
use Src\Shared\Domain\ValueObject\IntUnsignedValueObject;
use Src\Shop\Shared\Domain\Exception\OrderLineUnitNegativeException;

final class OrderLineUnits extends IntUnsignedValueObject
{
    public function __construct(int $value)
    {
        try {
            parent::__construct($value);
        } catch (InvalidArgumentException $e) {
            throw new OrderLineUnitNegativeException($value);
        }
    }

    public function isEmpty(): bool
    {
        return $this->value === 0;
    }
}
