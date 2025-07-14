<?php

declare(strict_types=1);

namespace Tests\Shop\ProductCategory\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Group;
use Src\Shared\Domain\Exception\InvalidStringLengthException;
use Src\Shop\ProductCategory\Domain\ValueObject\ProductCategoryName;
use Tests\Utils\UnitPhpUnitTestCase;

final class ProductCategoryNameTest extends UnitPhpUnitTestCase
{
    private const VALID_NAME = 'Running Shoes';
    private const TOO_SHORT_NAME = '';
    private const TOO_LONG_NAME = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eget ultricies tincidunt, nunc nisl aliquam nunc, quis aliquam nisl nunc quis nisl. Sed vitae nisl eget nisl aliquam aliquam. Nulla facilisi. Nulla facilisi. Nulla facilisi. Nulla facilisi. Nulla facilisi. Nulla facilisi. Nulla facilisi. Nulla facilisi. Nulla facilisi. Nulla facilisi. Nulla facilisi. Nulla facilisi. Nulla facilisi. Nulla facilisi. Nulla facilisi. Nulla facilisi. Nulla facilisi. Nulla facilisi. Nulla facilisi. Nulla facilisi. Nulla facilisi.';
    private const NAME_WITH_SPACES = '  Running Shoes  ';
    private const NAME_WITH_SPECIAL_CHARS = 'Zapatos de Running 2024';

    #[Group('unit')]
    public function test_create_valid_name(): void
    {
        // GIVEN
        $name = self::VALID_NAME;

        // WHEN
        $categoryName = new ProductCategoryName($name);

        // THEN
        $this->assertSame($name, $categoryName->value());
    }

    #[Group('unit')]
    public function test_create_name_with_spaces_gets_trimmed(): void
    {
        // GIVEN
        $name = self::NAME_WITH_SPACES;
        $expected = trim($name);

        // WHEN
        $categoryName = new ProductCategoryName($name);

        // THEN
        $this->assertSame($expected, $categoryName->value());
    }

    #[Group('unit')]
    public function test_create_name_with_special_characters(): void
    {
        // GIVEN
        $name = self::NAME_WITH_SPECIAL_CHARS;

        // WHEN
        $categoryName = new ProductCategoryName($name);

        // THEN (no exception should be thrown)
        $this->assertSame($name, $categoryName->value());
    }

    #[Group('unit')]
    public function test_create_too_short_name_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidStringLengthException::class);
        $this->expectExceptionMessage(
            'The length must be between <1> and <255> characters',
        );

        // WHEN
        new ProductCategoryName(self::TOO_SHORT_NAME);
    }

    #[Group('unit')]
    public function test_create_too_long_name_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidStringLengthException::class);
        $this->expectExceptionMessage(
            'The length must be between <1> and <255> characters',
        );

        // WHEN
        new ProductCategoryName(self::TOO_LONG_NAME);
    }

    #[Group('unit')]
    public function test_equals_instances(): void
    {
        // GIVEN
        $name1 = new ProductCategoryName(self::VALID_NAME);
        $name2 = new ProductCategoryName(self::VALID_NAME);
        $differentName = new ProductCategoryName('Casual Shoes');

        // THEN
        $this->assertTrue($name1->equals($name2));
        $this->assertFalse($name1->equals($differentName));
    }
}
