<?php

declare(strict_types=1);

namespace Tests\Shop\Shared\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Group;
use Src\Shared\Domain\Exception\InvalidUuidException;
use Src\Shop\Shared\Domain\ValueObject\CustomerId;
use Tests\Utils\UnitPhpUnitTestCase;

final class CustomerIdTest extends UnitPhpUnitTestCase
{
    #[Group('unit')]
    public function test_create_with_valid_uuid(): void
    {
        // WHEN
        $customerId = new CustomerId(self::VALID_UUID);

        // THEN
        $this->assertSame(self::VALID_UUID, $customerId->value());
    }

    #[Group('unit')]
    public function test_generate_new_instance(): void
    {
        // WHEN
        $customerId = CustomerId::generate();

        // THEN
        $this->assertInstanceOf(CustomerId::class, $customerId);
        $this->assertNotEmpty($customerId->value());
    }

    #[Group('unit')]
    public function test_equals_instances(): void
    {
        // GIVEN
        $customerId1 = new CustomerId(self::VALID_UUID);
        $customerId2 = new CustomerId(self::VALID_UUID);
        $differentCustomerId = CustomerId::generate();

        // THEN
        $this->assertTrue($customerId1->equals($customerId2));
        $this->assertFalse($customerId1->equals($differentCustomerId));
    }

    #[Group('unit')]
    public function test_invalid_uuid_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidUuidException::class);
        $this->expectExceptionMessage('<Customer Id> does not allow the value uuid <' . self::INVALID_UUID . '>.');

        // WHEN
        new CustomerId(self::INVALID_UUID);
    }
}
