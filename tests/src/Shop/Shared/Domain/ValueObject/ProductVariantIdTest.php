<?php

declare(strict_types=1);

namespace Tests\Shop\Shared\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Group;
use Src\Shared\Domain\Exception\InvalidUuidException;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantId;
use Tests\Utils\UnitPhpUnitTestCase;

final class ProductVariantIdTest extends UnitPhpUnitTestCase
{
    #[Group('unit')]
    public function test_create_with_valid_uuid(): void
    {
        // WHEN
        $productVariantId = new ProductVariantId(self::VALID_UUID);

        // THEN
        $this->assertSame(self::VALID_UUID, $productVariantId->value());
    }

    #[Group('unit')]
    public function test_generate_new_instance(): void
    {
        // WHEN
        $productVariantId = ProductVariantId::generate();

        // THEN
        $this->assertInstanceOf(ProductVariantId::class, $productVariantId);
        $this->assertNotEmpty($productVariantId->value());
    }

    #[Group('unit')]
    public function test_equals_instances(): void
    {
        // GIVEN
        $productVariantId1 = new ProductVariantId(self::VALID_UUID);
        $productVariantId2 = new ProductVariantId(self::VALID_UUID);
        $differentProductVariantId = ProductVariantId::generate();

        // THEN
        $this->assertTrue($productVariantId1->equals($productVariantId2));
        $this->assertFalse($productVariantId1->equals($differentProductVariantId));
    }

    #[Group('unit')]
    public function test_invalid_uuid_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidUuidException::class);
        $this->expectExceptionMessage('<Product Variant Id> does not allow the value uuid <' . self::INVALID_UUID . '>.');

        // WHEN
        new ProductVariantId(self::INVALID_UUID);
    }
}
