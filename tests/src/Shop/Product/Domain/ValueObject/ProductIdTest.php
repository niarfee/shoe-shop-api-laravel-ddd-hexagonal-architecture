<?php

declare(strict_types=1);

namespace Tests\Shop\Product\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Group;
use Src\Shared\Domain\Exception\InvalidUuidException;
use Src\Shop\Product\Domain\ValueObject\ProductId;
use Symfony\Component\Uid\Uuid;
use Tests\Utils\UnitPhpUnitTestCase;

final class ProductIdTest extends UnitPhpUnitTestCase
{
    #[Group('unit')]
    public function test_create_valid_uuid(): void
    {
        // GIVEN
        $uuid = self::VALID_UUID;

        // WHEN
        $productId = new ProductId($uuid);

        // THEN
        $this->assertSame($uuid, $productId->value());
    }

    #[Group('unit')]
    public function test_generate_new_uuid(): void
    {
        // WHEN
        $productId = ProductId::generate();

        // THEN
        $this->assertNotEmpty($productId->value());
        $this->assertTrue(Uuid::isValid($productId->value()));
    }

    #[Group('unit')]
    public function test_invalid_uuid_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidUuidException::class);
        $this->expectExceptionMessage('<Product Id> does not allow the value uuid <' . self::INVALID_UUID . '>.');

        // WHEN
        new ProductId(self::INVALID_UUID);
    }

    #[Group('unit')]
    public function test_equals(): void
    {
        // GIVEN
        $uuid1 = new ProductId(self::VALID_UUID);
        $uuid2 = new ProductId(self::VALID_UUID);
        $uuid3 = ProductId::generate();

        // THEN
        $this->assertTrue($uuid1->equals($uuid2));
        $this->assertFalse($uuid1->equals($uuid3));
    }

    #[Group('unit')]
    public function test_value_method_returns_correct_value(): void
    {
        // GIVEN
        $expectedValue = self::VALID_UUID;
        $productId = new ProductId($expectedValue);

        // WHEN
        $value = $productId->value();

        // THEN
        $this->assertSame($expectedValue, $value);
    }

    #[Group('unit')]
    public function test_empty_uuid_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidUuidException::class);
        $this->expectExceptionMessage('<Product Id> does not allow the value uuid <>.');

        // WHEN
        new ProductId('');
    }
}
