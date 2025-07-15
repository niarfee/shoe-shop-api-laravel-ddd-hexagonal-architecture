<?php

declare(strict_types=1);

namespace Tests\Shop\Product\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Group;
use Src\Shop\Product\Domain\Exception\InvalidProductNameException;
use Src\Shop\Product\Domain\ValueObject\ProductName;
use Tests\Utils\UnitPhpUnitTestCase;

final class ProductNameTest extends UnitPhpUnitTestCase
{
    private const MAX_LENGTH = 50;
    private const VALID_NAME = 'Running Shoes';

    #[Group('unit')]
    public function test_create_valid_name(): void
    {
        // GIVEN
        $name = self::VALID_NAME;

        // WHEN
        $productName = new ProductName($name);

        // THEN
        $this->assertSame($name, $productName->value());
    }

    #[Group('unit')]
    public function test_create_name_with_max_length(): void
    {
        // GIVEN
        $maxLengthName = str_repeat('a', self::MAX_LENGTH);

        // WHEN
        $productName = new ProductName($maxLengthName);

        // THEN (no exception should be thrown)
        $this->assertSame($maxLengthName, $productName->value());
    }

    #[Group('unit')]
    public function test_create_name_exceeding_max_length_throws_exception(): void
    {
        // GIVEN
        $tooLongName = str_repeat('a', self::MAX_LENGTH + 1);
        $this->expectException(InvalidProductNameException::class);
        $this->expectExceptionMessageMatches('/Invalid product name, the length must be between <\d+> and <\d+> characters./');

        // WHEN
        new ProductName($tooLongName);
    }

    #[Group('unit')]
    public function test_empty_name_is_invalid(): void
    {
        // GIVEN
        $emptyName = '';
        $this->expectException(InvalidProductNameException::class);
        $this->expectExceptionMessageMatches('/Invalid product name, the length must be between <\d+> and <\d+> characters./');

        // WHEN
        new ProductName($emptyName);
    }

    #[Group('unit')]
    public function test_whitespace_only_name_is_invalid(): void
    {
        // GIVEN
        $whitespaceName = '   ';
        $this->expectException(InvalidProductNameException::class);
        $this->expectExceptionMessageMatches('/Invalid product name, the length must be between <\d+> and <\d+> characters./');

        // WHEN
        new ProductName($whitespaceName);
    }

    #[Group('unit')]
    public function test_value_method_returns_correct_value(): void
    {
        // GIVEN
        $expectedValue = self::VALID_NAME;
        $productName = new ProductName($expectedValue);

        // WHEN
        $value = $productName->value();

        // THEN
        $this->assertSame($expectedValue, $value);
    }
}
