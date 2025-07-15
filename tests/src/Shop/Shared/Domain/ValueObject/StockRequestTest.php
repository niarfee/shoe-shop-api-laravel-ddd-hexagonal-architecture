<?php

declare(strict_types=1);

namespace Tests\Shop\Shared\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Group;
use Src\Shop\Shared\Domain\ValueObject\OrderLineUnits;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantStock;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantStockRequested;
use Src\Shop\Shared\Domain\ValueObject\StockRequest;
use Tests\Utils\UnitPhpUnitTestCase;

final class StockRequestTest extends UnitPhpUnitTestCase
{
    #[Group('unit')]
    public function test_to_order_line_units_with_sufficient_stock(): void
    {
        // GIVEN
        $stockRequested = new ProductVariantStockRequested(5);
        $stockAvailable = new ProductVariantStock(10);
        $stockRequest = new StockRequest($stockRequested, $stockAvailable);

        // WHEN
        $orderLineUnits = $stockRequest->toOrderLineUnits();

        // THEN
        $this->assertInstanceOf(OrderLineUnits::class, $orderLineUnits);
        $this->assertSame(5, $orderLineUnits->value());
    }

    #[Group('unit')]
    public function test_to_order_line_units_with_insufficient_stock(): void
    {
        // GIVEN
        $stockRequested = new ProductVariantStockRequested(10);
        $stockAvailable = new ProductVariantStock(5);
        $stockRequest = new StockRequest($stockRequested, $stockAvailable);

        // WHEN
        $orderLineUnits = $stockRequest->toOrderLineUnits();

        // THEN
        $this->assertInstanceOf(OrderLineUnits::class, $orderLineUnits);
        $this->assertSame(5, $orderLineUnits->value());
    }

    #[Group('unit')]
    public function test_to_order_line_units_with_exact_stock(): void
    {
        // GIVEN
        $stockRequested = new ProductVariantStockRequested(5);
        $stockAvailable = new ProductVariantStock(5);
        $stockRequest = new StockRequest($stockRequested, $stockAvailable);

        // WHEN
        $orderLineUnits = $stockRequest->toOrderLineUnits();

        // THEN
        $this->assertInstanceOf(OrderLineUnits::class, $orderLineUnits);
        $this->assertSame(5, $orderLineUnits->value());
    }

    #[Group('unit')]
    public function test_to_order_line_units_with_zero_requested(): void
    {
        // GIVEN
        $stockRequested = new ProductVariantStockRequested(0);
        $stockAvailable = new ProductVariantStock(10);
        $stockRequest = new StockRequest($stockRequested, $stockAvailable);

        // WHEN
        $orderLineUnits = $stockRequest->toOrderLineUnits();

        // THEN
        $this->assertInstanceOf(OrderLineUnits::class, $orderLineUnits);
        $this->assertSame(0, $orderLineUnits->value());
        $this->assertTrue($orderLineUnits->isEmpty());
    }

    #[Group('unit')]
    public function test_to_order_line_units_with_zero_available(): void
    {
        // GIVEN
        $stockRequested = new ProductVariantStockRequested(5);
        $stockAvailable = new ProductVariantStock(0);
        $stockRequest = new StockRequest($stockRequested, $stockAvailable);

        // WHEN
        $orderLineUnits = $stockRequest->toOrderLineUnits();

        // THEN
        $this->assertInstanceOf(OrderLineUnits::class, $orderLineUnits);
        $this->assertSame(0, $orderLineUnits->value());
        $this->assertTrue($orderLineUnits->isEmpty());
    }

    #[Group('unit')]
    public function test_create_static_factory_method(): void
    {
        // GIVEN
        $stockRequested = new ProductVariantStockRequested(5);
        $stockAvailable = new ProductVariantStock(10);

        // WHEN
        $stockRequest = StockRequest::create($stockRequested, $stockAvailable);

        // THEN
        $this->assertInstanceOf(StockRequest::class, $stockRequest);
        $orderLineUnits = $stockRequest->toOrderLineUnits();
        $this->assertSame(5, $orderLineUnits->value());
    }
}
