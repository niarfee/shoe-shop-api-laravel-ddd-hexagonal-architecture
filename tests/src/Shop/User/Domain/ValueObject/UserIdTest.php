<?php

declare(strict_types=1);

namespace Tests\Shop\User\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Group;
use Src\Shared\Domain\Exception\InvalidUuidException;
use Src\Shop\User\Domain\ValueObject\UserId;
use Symfony\Component\Uid\Uuid;
use Tests\Utils\UnitPhpUnitTestCase;

final class UserIdTest extends UnitPhpUnitTestCase
{
    #[Group('unit')]
    public function test_create_valid_uuid(): void
    {
        // GIVEN
        $uuid = self::VALID_UUID;

        // WHEN
        $userId = new UserId($uuid);

        // THEN
        $this->assertSame($uuid, $userId->value());
    }

    #[Group('unit')]
    public function test_generate_new_uuid(): void
    {
        // WHEN
        $userId = UserId::generate();

        // THEN
        $this->assertNotEmpty($userId->value());
        $this->assertTrue(Uuid::isValid($userId->value()));
    }

    #[Group('unit')]
    public function test_invalid_uuid_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidUuidException::class);
        $this->expectExceptionMessage('<User Id> does not allow the value uuid <' . self::INVALID_UUID . '>.');

        // WHEN
        new UserId(self::INVALID_UUID);
    }

    #[Group('unit')]
    public function test_equals(): void
    {
        // GIVEN
        $uuid1 = new UserId(self::VALID_UUID);
        $uuid2 = new UserId(self::VALID_UUID);
        $uuid3 = UserId::generate();

        // THEN
        $this->assertTrue($uuid1->equals($uuid2));
        $this->assertFalse($uuid1->equals($uuid3));
    }

    #[Group('unit')]
    public function test_value_method_returns_correct_value(): void
    {
        // GIVEN
        $expectedValue = self::VALID_UUID;
        $userId = new UserId($expectedValue);

        // WHEN
        $value = $userId->value();

        // THEN
        $this->assertSame($expectedValue, $value);
    }

    #[Group('unit')]
    public function test_empty_uuid_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidUuidException::class);
        $this->expectExceptionMessage('<User Id> does not allow the value uuid <>.');

        // WHEN
        new UserId('');
    }
}
