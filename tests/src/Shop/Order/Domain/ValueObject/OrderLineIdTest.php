<?php

declare(strict_types=1);

namespace Tests\Shop\Order\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Group;
use Src\Shared\Domain\Exception\InvalidUuidException;
use Src\Shop\Order\Domain\ValueObject\OrderLineId;
use Symfony\Component\Uid\Uuid;
use Tests\Utils\UnitPhpUnitTestCase;

final class OrderLineIdTest extends UnitPhpUnitTestCase
{
    #[Group('unit')]
    public function test_create_valid_order_line_id(): void
    {
        // GIVEN
        $uuid = self::VALID_UUID;

        // WHEN
        $orderLineId = new OrderLineId($uuid);

        // THEN
        $this->assertSame($uuid, $orderLineId->value());
    }

    #[Group('unit')]
    public function test_generate_new_order_line_id(): void
    {
        // WHEN
        $orderLineId = OrderLineId::generate();

        // THEN
        $this->assertNotEmpty($orderLineId->value());
        $this->assertTrue(Uuid::isValid($orderLineId->value()));
    }

    #[Group('unit')]
    public function test_invalid_uuid_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidUuidException::class);
        $this->expectExceptionMessage('<Order Line Id> does not allow the value uuid <' . self::INVALID_UUID . '>.');

        // WHEN
        new OrderLineId(self::INVALID_UUID);
    }

    #[Group('unit')]
    public function test_equals(): void
    {
        // GIVEN
        $orderLineId1 = new OrderLineId(self::VALID_UUID);
        $orderLineId2 = new OrderLineId(self::VALID_UUID);
        $orderLineId3 = OrderLineId::generate();

        // THEN
        $this->assertTrue($orderLineId1->equals($orderLineId2));
        $this->assertFalse($orderLineId1->equals($orderLineId3));
    }

    #[Group('unit')]
    public function test_value_method_returns_correct_value(): void
    {
        // GIVEN
        $expectedValue = self::VALID_UUID;
        $orderLineId = new OrderLineId($expectedValue);

        // WHEN
        $value = $orderLineId->value();

        // THEN
        $this->assertSame($expectedValue, $value);
    }

    #[Group('unit')]
    public function test_empty_uuid_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidUuidException::class);
        $this->expectExceptionMessage('<Order Line Id> does not allow the value uuid <>.');

        // WHEN
        new OrderLineId('');
    }
}
