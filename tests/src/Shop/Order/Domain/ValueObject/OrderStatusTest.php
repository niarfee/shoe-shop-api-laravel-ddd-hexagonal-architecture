<?php

declare(strict_types=1);

namespace Tests\Shop\Order\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Group;
use Src\Shop\Order\Domain\Enum\OrderStatusEnum;
use Src\Shop\Order\Domain\Exception\InvalidOrderStatusException;
use Src\Shop\Order\Domain\ValueObject\OrderStatus;
use Tests\Utils\UnitPhpUnitTestCase;

final class OrderStatusTest extends UnitPhpUnitTestCase
{
    // Test named constructors
    #[Group('unit')]
    public function test_in_cart_named_constructor(): void
    {
        // WHEN
        $status = OrderStatus::inCart();

        // THEN
        $this->assertSame(OrderStatusEnum::InCart->value, $status->value());
        $this->assertSame(OrderStatusEnum::InCart->label(), $status->label());
    }

    #[Group('unit')]
    public function test_pending_shipment_named_constructor(): void
    {
        // WHEN
        $status = OrderStatus::pendingShipment();

        // THEN
        $this->assertSame(OrderStatusEnum::PendingShipment->value, $status->value());
        $this->assertSame(OrderStatusEnum::PendingShipment->label(), $status->label());
    }

    #[Group('unit')]
    public function test_sent_named_constructor(): void
    {
        // WHEN
        $status = OrderStatus::sent();

        // THEN
        $this->assertSame(OrderStatusEnum::Sent->value, $status->value());
        $this->assertSame(OrderStatusEnum::Sent->label(), $status->label());
    }

    #[Group('unit')]
    public function test_completed_named_constructor(): void
    {
        // WHEN
        $status = OrderStatus::completed();

        // THEN
        $this->assertSame(OrderStatusEnum::Completed->value, $status->value());
        $this->assertSame(OrderStatusEnum::Completed->label(), $status->label());
    }

    // Other tests
    #[Group('unit')]
    public function test_static_label_methods(): void
    {
        $this->assertSame('In Cart', OrderStatus::inCartLabel());
        $this->assertSame('Pending shipment', OrderStatus::pendingShipmentLabel());
        $this->assertSame('Sent', OrderStatus::sentLabel());
        $this->assertSame('Completed', OrderStatus::completedLabel());
    }

    #[Group('unit')]
    public function test_invalid_status_throws_exception(): void
    {
        // GIVEN
        $invalidStatus = 999;
        $this->expectException(InvalidOrderStatusException::class);
        $this->expectExceptionMessage('Order status <' . $invalidStatus . '> invalid.');

        // WHEN
        new OrderStatus($invalidStatus);
    }

    #[Group('unit')]
    public function test_status_values_available(): void
    {
        // WHEN
        $availableStatuses = OrderStatus::statusValuesAvailable();

        // THEN
        $expectedStatuses = array_map(
            fn (OrderStatusEnum $status) => $status->value,
            OrderStatusEnum::cases(),
        );
        $this->assertSame($expectedStatuses, $availableStatuses);
    }

    #[Group('unit')]
    public function test_label_method(): void
    {
        // GIVEN
        $status = new OrderStatus(OrderStatusEnum::InCart->value);

        // WHEN
        $label = $status->label();

        // THEN
        $this->assertSame(OrderStatusEnum::InCart->label(), $label);
    }

    #[Group('unit')]
    public function test_equals(): void
    {
        // GIVEN
        $status1 = new OrderStatus(OrderStatusEnum::InCart->value);
        $status2 = new OrderStatus(OrderStatusEnum::InCart->value);
        $status3 = new OrderStatus(OrderStatusEnum::Completed->value);

        // THEN
        $this->assertTrue($status1->equals($status2));
        $this->assertFalse($status1->equals($status3));
    }

    #[Group('unit')]
    public function test_value_method_returns_correct_value(): void
    {
        // GIVEN
        $expectedValue = OrderStatusEnum::InCart->value;
        $status = new OrderStatus($expectedValue);

        // WHEN
        $value = $status->value();

        // THEN
        $this->assertSame($expectedValue, $value);
    }
}
