<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Shared\Domain\ValueObject;

use Src\Shop\Shared\Domain\ValueObject\ProductVariantStock;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantStockRequested;
use Src\Shop\Shared\Domain\ValueObject\StockRequest;

final class StockRequestMother
{
    public static function make(
        ?ProductVariantStockRequested $stockRequested = null,
        ?ProductVariantStock $stockAvailable = null,
    ): StockRequest {
        return StockRequest::create(
            stockRequested: $stockRequested ?? ProductVariantStockRequestedMother::make(),
            stockAvailable: $stockAvailable ?? ProductVariantStockMother::make(),
        );
    }
}
