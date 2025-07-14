<?php

declare(strict_types=1);

namespace Tests\Shop\ProductCategory\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Group;
use Src\Shared\Domain\Exception\InvalidStringLengthException;
use Src\Shop\ProductCategory\Domain\Exception\InvalidProductCategorySlugException;
use Src\Shop\ProductCategory\Domain\ValueObject\ProductCategorySlug;
use Tests\Utils\UnitPhpUnitTestCase;

final class ProductCategorySlugTest extends UnitPhpUnitTestCase
{
    private const VALID_SLUG = 'running-shoes';
    private const VALID_SLUG_WITH_NUMBERS = 'running-shoes-2024';
    private const INVALID_SLUG = 'running_shoes';
    private const TOO_SHORT_SLUG = '';
    private const TOO_LONG_SLUG = 'lorem-ipsum-dolor-sit-amet-consectetur-adipiscing-elit-nullam-auctor-nisl-eget-ultricies-tincidunt-nunc-nisl-aliquam-nunc-quis-aliquam-nisl-nunc-quis-nisl-lorem-ipsum-dolor-sit-amet-consectetur-adipiscing-elit-nullam-auctor-nisl-eget-ultricies-tincidunt-nunc';
    private const SLUG_WITH_SPACES = '  running-shoes  ';

    #[Group('unit')]
    public function test_create_valid_slug(): void
    {
        // GIVEN
        $slug = self::VALID_SLUG;

        // WHEN
        $productCategorySlug = new ProductCategorySlug($slug);

        // THEN
        $this->assertSame($slug, $productCategorySlug->value());
    }

    #[Group('unit')]
    public function test_create_valid_slug_with_numbers(): void
    {
        // GIVEN
        $slug = self::VALID_SLUG_WITH_NUMBERS;

        // WHEN
        $productCategorySlug = new ProductCategorySlug($slug);

        // THEN (no exception should be thrown)
        $this->assertSame($slug, $productCategorySlug->value());
    }

    #[Group('unit')]
    public function test_create_slug_with_spaces_gets_trimmed(): void
    {
        // GIVEN
        $slug = self::SLUG_WITH_SPACES;
        $expected = trim($slug);

        // WHEN
        $productCategorySlug = new ProductCategorySlug($slug);

        // THEN
        $this->assertSame($expected, $productCategorySlug->value());
    }

    #[Group('unit')]
    public function test_create_invalid_slug_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidProductCategorySlugException::class);
        $this->expectExceptionMessage(
            'Invalid product category slug, please use only letters, numbers and hyphens. Got <running_shoes>.',
        );

        // WHEN
        new ProductCategorySlug(self::INVALID_SLUG);
    }

    #[Group('unit')]
    public function test_create_too_short_slug_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidStringLengthException::class);
        $this->expectExceptionMessage(
            'The length must be between <1> and <255> characters.',
        );

        // WHEN
        new ProductCategorySlug(self::TOO_SHORT_SLUG);
    }

    #[Group('unit')]
    public function test_create_too_long_slug_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidStringLengthException::class);
        $this->expectExceptionMessage(
            'The length must be between <1> and <255> characters.',
        );

        // WHEN
        new ProductCategorySlug(self::TOO_LONG_SLUG);
    }

    #[Group('unit')]
    public function test_equals_instances(): void
    {
        // GIVEN
        $slug1 = new ProductCategorySlug(self::VALID_SLUG);
        $slug2 = new ProductCategorySlug(self::VALID_SLUG);
        $differentSlug = new ProductCategorySlug('casual-shoes');

        // THEN
        $this->assertTrue($slug1->equals($slug2));
        $this->assertFalse($slug1->equals($differentSlug));
    }
}
