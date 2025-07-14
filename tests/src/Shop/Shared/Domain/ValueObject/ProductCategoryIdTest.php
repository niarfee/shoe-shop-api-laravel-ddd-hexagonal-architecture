<?php

declare(strict_types=1);

namespace Tests\Shop\Shared\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Group;
use Src\Shared\Domain\Exception\InvalidUuidException;
use Src\Shop\Shared\Domain\ValueObject\ProductCategoryId;
use Tests\Utils\UnitPhpUnitTestCase;

final class ProductCategoryIdTest extends UnitPhpUnitTestCase
{
    #[Group('unit')]
    public function test_create_with_valid_uuid(): void
    {
        // WHEN
        $productCategoryId = new ProductCategoryId(self::VALID_UUID);

        // THEN
        $this->assertSame(self::VALID_UUID, $productCategoryId->value());
    }

    #[Group('unit')]
    public function test_generate_new_instance(): void
    {
        // WHEN
        $productCategoryId = ProductCategoryId::generate();

        // THEN
        $this->assertInstanceOf(ProductCategoryId::class, $productCategoryId);
        $this->assertNotEmpty($productCategoryId->value());
    }

    #[Group('unit')]
    public function test_equals_instances(): void
    {
        // GIVEN
        $productCategoryId1 = new ProductCategoryId(self::VALID_UUID);
        $productCategoryId2 = new ProductCategoryId(self::VALID_UUID);
        $differentProductCategoryId = ProductCategoryId::generate();

        // THEN
        $this->assertTrue($productCategoryId1->equals($productCategoryId2));
        $this->assertFalse($productCategoryId1->equals($differentProductCategoryId));
    }

    #[Group('unit')]
    public function test_invalid_uuid_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidUuidException::class);
        $this->expectExceptionMessage('<Product Category Id> does not allow the value uuid <' . self::INVALID_UUID . '>.');

        // WHEN
        new ProductCategoryId(self::INVALID_UUID);
    }
}
