<?php

declare(strict_types=1);

namespace Src\Shop\Order\Domain\ValueObject;

use Src\Shared\Domain\Utils\MoneyUtils;
use Src\Shared\Domain\ValueObject\PriceValueObject;
use Src\Shop\Shared\Domain\ValueObject\OrderLineUnits;

final class OrderLineTotalPrice extends PriceValueObject
{
    public function __construct(OrderLineUnitPrice $unitPrice, OrderLineUnits $units)
    {
        $total = MoneyUtils::multiply($unitPrice->value(), $units->value());
        parent::__construct($total);
    }
}
