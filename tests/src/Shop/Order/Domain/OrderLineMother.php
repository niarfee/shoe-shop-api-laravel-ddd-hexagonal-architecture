<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Order\Domain;

use Src\Shop\Order\Domain\OrderLine;
use Src\Shop\Order\Domain\ValueObject\OrderId;
use Src\Shop\Order\Domain\ValueObject\OrderLineId;
use Src\Shop\Order\Domain\ValueObject\OrderLineUnitPrice;
use Src\Shop\Shared\Domain\ValueObject\OrderLineUnits;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantId;
use Tests\Src\Shop\Order\Domain\ValueObject\OrderIdMother;
use Tests\Src\Shop\Order\Domain\ValueObject\OrderLineIdMother;
use Tests\Src\Shop\Order\Domain\ValueObject\OrderLineUnitPriceMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\OrderLineUnitsMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\ProductVariantIdMother;

final class OrderLineMother
{
    public static function make(
        ?OrderLineId $id = null,
        ?OrderId $orderId = null,
        ?ProductVariantId $productVariantId = null,
        ?OrderLineUnits $units = null,
        ?OrderLineUnitPrice $unitPrice = null,
    ): OrderLine {
        return OrderLine::create(
            id: $id ?? OrderLineIdMother::make(),
            orderId: $orderId ?? OrderIdMother::make(),
            productVariantId: $productVariantId ?? ProductVariantIdMother::make(),
            units: $units ?? OrderLineUnitsMother::make(),
            unitPrice: $unitPrice ?? OrderLineUnitPriceMother::make(),
        );
    }
}
