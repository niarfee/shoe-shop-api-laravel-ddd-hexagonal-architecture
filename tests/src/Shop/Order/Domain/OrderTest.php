<?php

declare(strict_types=1);

namespace Tests\Shop\Order\Domain;

use PHPUnit\Framework\Attributes\Group;
use Src\Shop\Order\Domain\Exception\OrderAlreadyContainsOrderLineForProductVariantException;
use Src\Shop\Order\Domain\Exception\OrderLineDoesNotBelongToOrderException;
use Src\Shop\Order\Domain\Exception\OrderLineNotFoundException;
use Src\Shop\Order\Domain\Exception\OrderNotInCartException;
use Src\Shop\Order\Domain\ValueObject\OrderLineId;
use Src\Shop\Order\Domain\ValueObject\OrderStatus;
use Tests\Src\Shop\Order\Domain\OrderLineMother;
use Tests\Src\Shop\Order\Domain\OrderMother;
use Tests\Src\Shop\Order\Domain\ValueObject\OrderLineUnitPriceMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\ProductVariantIdMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\StockRequestMother;
use Tests\Utils\UnitPhpUnitTestCase;

class OrderTest extends UnitPhpUnitTestCase
{
    #[Group('unit')]
    public function test_can_add_multiple_different_product_variants()
    {
        // GIVEN
        $order = OrderMother::make();
        $firstLine = OrderLineMother::make(
            orderId: $order->id(),
            productVariantId: ProductVariantIdMother::make(),
        );
        $secondLine = OrderLineMother::make(
            orderId: $order->id(),
            productVariantId: ProductVariantIdMother::make(),
        );

        // WHEN
        $order->addLine($firstLine);
        $order->addLine($secondLine);

        // THEN - No exception should be thrown
        $this->assertCount(2, $order->lines());
    }

    #[Group('unit')]
    public function test_duplicate_product_variant_error_message_contains_correct_ids()
    {
        // GIVEN
        $order = OrderMother::make();
        $productVariantId = ProductVariantIdMother::make();
        $firstLine = OrderLineMother::make(
            orderId: $order->id(),
            productVariantId: $productVariantId,
        );
        $duplicateLine = OrderLineMother::make(
            orderId: $order->id(),
            productVariantId: $productVariantId,
        );

        // WHEN
        $order->addLine($firstLine);

        // THEN
        $this->expectException(OrderAlreadyContainsOrderLineForProductVariantException::class);
        $this->expectExceptionMessage(
            'Order <' . $order->id()->value() . '> already contains a OrderLine for ProductVariant <' . $productVariantId->value() . '>.',
        );

        $order->addLine($duplicateLine);
    }

    #[Group('unit')]
    public function test_cannot_update_line_units_when_not_in_cart()
    {
        // GIVEN
        $order = OrderMother::make(status: OrderStatus::pendingShipment());
        $orderLine = OrderLineMother::make(orderId: $order->id());
        $order->addLine($orderLine);
        $stockRequest = StockRequestMother::make();

        // THEN
        $this->expectException(OrderNotInCartException::class);
        $this->expectExceptionMessage(
            "The Order is not 'In Cart' OrderStatus and its OrderLines cannot be modified. Current OrderStatus: <" . $order->status()->label() . '>.',
        );

        // WHEN
        $order->updateLineUnitsBasedOnStock($orderLine->id(), $stockRequest);
    }

    #[Group('unit')]
    public function test_cannot_update_line_unit_price_when_not_in_cart()
    {
        // GIVEN
        $order = OrderMother::make(status: OrderStatus::pendingShipment());
        $orderLine = OrderLineMother::make(orderId: $order->id());
        $order->addLine($orderLine);
        $newPrice = OrderLineUnitPriceMother::make(99.99);

        // THEN
        $this->expectException(OrderNotInCartException::class);
        $this->expectExceptionMessage(
            "The Order is not 'In Cart' OrderStatus and its OrderLines cannot be modified. Current OrderStatus: <" . $order->status()->label() . '>.',
        );

        // WHEN
        $order->updateLineUnitPrice($orderLine->id(), $newPrice);
    }

    #[Group('unit')]
    public function test_throw_exception_when_line_not_found()
    {
        // GIVEN
        $order = OrderMother::make();
        $nonExistentLineId = OrderLineId::generate();

        // THEN
        $this->expectException(OrderLineNotFoundException::class);
        $this->expectExceptionMessage('OrderLine <' . $nonExistentLineId->value() . '> not found.');

        // WHEN
        $order->lineById($nonExistentLineId);
    }

    #[Group('unit')]
    public function test_throw_exception_when_adding_line_from_different_order()
    {
        // GIVEN
        $order = OrderMother::make();
        $differentOrder = OrderMother::make();
        $lineFromDifferentOrder = OrderLineMother::make(orderId: $differentOrder->id());

        // THEN
        $this->expectException(OrderLineDoesNotBelongToOrderException::class);
        $this->expectExceptionMessage(
            'OrderLine <' . $lineFromDifferentOrder->id()->value() . '> with OrderId <' . $lineFromDifferentOrder->orderId()->value() . '> does not belong to Order with OrderId <' . $order->id()->value() . '>.',
        );

        // WHEN
        $order->addLine($lineFromDifferentOrder);
    }
}
