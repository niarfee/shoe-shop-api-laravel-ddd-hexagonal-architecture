<?php

declare(strict_types=1);

namespace Tests\Shop\Order\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Group;
use Src\Shared\Domain\Exception\InvalidUuidException;
use Src\Shop\Order\Domain\ValueObject\OrderId;
use Symfony\Component\Uid\Uuid;
use Tests\Utils\UnitPhpUnitTestCase;

final class OrderIdTest extends UnitPhpUnitTestCase
{
    #[Group('unit')]
    public function test_create_valid_order_id(): void
    {
        // GIVEN
        $uuid = self::VALID_UUID;

        // WHEN
        $orderId = new OrderId($uuid);

        // THEN
        $this->assertSame($uuid, $orderId->value());
    }

    #[Group('unit')]
    public function test_generate_new_order_id(): void
    {
        // WHEN
        $orderId = OrderId::generate();

        // THEN
        $this->assertNotEmpty($orderId->value());
        $this->assertTrue(Uuid::isValid($orderId->value()));
    }

    #[Group('unit')]
    public function test_invalid_uuid_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidUuidException::class);
        $this->expectExceptionMessage('<Order Id> does not allow the value uuid <' . self::INVALID_UUID . '>.');

        // WHEN
        new OrderId(self::INVALID_UUID);
    }

    #[Group('unit')]
    public function test_equals(): void
    {
        // GIVEN
        $orderId1 = new OrderId(self::VALID_UUID);
        $orderId2 = new OrderId(self::VALID_UUID);
        $orderId3 = OrderId::generate();

        // THEN
        $this->assertTrue($orderId1->equals($orderId2));
        $this->assertFalse($orderId1->equals($orderId3));
    }

    #[Group('unit')]
    public function test_value_method_returns_correct_value(): void
    {
        // GIVEN
        $expectedValue = self::VALID_UUID;
        $orderId = new OrderId($expectedValue);

        // WHEN
        $value = $orderId->value();

        // THEN
        $this->assertSame($expectedValue, $value);
    }

    #[Group('unit')]
    public function test_empty_uuid_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidUuidException::class);
        $this->expectExceptionMessage('<Order Id> does not allow the value uuid <>.');

        // WHEN
        new OrderId('');
    }
}
