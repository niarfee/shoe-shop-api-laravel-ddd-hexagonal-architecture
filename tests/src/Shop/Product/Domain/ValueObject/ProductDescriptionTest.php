<?php

declare(strict_types=1);

namespace Tests\Shop\Product\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Group;
use Src\Shop\Product\Domain\Exception\InvalidProductDescriptionException;
use Src\Shop\Product\Domain\ValueObject\ProductDescription;
use Tests\Utils\UnitPhpUnitTestCase;

final class ProductDescriptionTest extends UnitPhpUnitTestCase
{
    private const MIN_LENGTH = 1;
    private const MAX_LENGTH = 300;
    private const VALID_DESCRIPTION = 'High-quality product with amazing features';
    private string $tooLongDescription;

    #[Group('unit')]
    public function test_create_valid_description(): void
    {
        // GIVEN
        $description = self::VALID_DESCRIPTION;

        // WHEN
        $productDescription = new ProductDescription($description);

        // THEN
        $this->assertSame($description, $productDescription->value());
    }

    #[Group('unit')]
    public function test_create_description_with_min_length(): void
    {
        // GIVEN
        $minLengthDescription = str_repeat('a', self::MIN_LENGTH);

        // WHEN
        $productDescription = new ProductDescription($minLengthDescription);

        // THEN (no exception should be thrown)
        $this->assertSame($minLengthDescription, $productDescription->value());
    }

    #[Group('unit')]
    public function test_create_description_with_max_length(): void
    {
        // GIVEN
        $maxLengthDescription = str_repeat('a', self::MAX_LENGTH);

        // WHEN
        $productDescription = new ProductDescription($maxLengthDescription);

        // THEN (no exception should be thrown)
        $this->assertSame($maxLengthDescription, $productDescription->value());
    }

    #[Group('unit')]
    public function test_create_description_exceeding_max_length_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidProductDescriptionException::class);
        $this->expectExceptionMessage(
            'Invalid product description, the length must be between <' . self::MIN_LENGTH . '> and <' . self::MAX_LENGTH . '> characters.',
        );

        // WHEN
        new ProductDescription($this->tooLongDescription);
    }

    #[Group('unit')]
    public function test_empty_description_throws_exception(): void
    {
        // GIVEN
        $emptyDescription = '';
        $this->expectException(InvalidProductDescriptionException::class);
        $this->expectExceptionMessage(
            'Invalid product description, the length must be between <' . self::MIN_LENGTH . '> and <' . self::MAX_LENGTH . '> characters.',
        );

        // WHEN
        new ProductDescription($emptyDescription);
    }

    #[Group('unit')]
    public function test_whitespace_only_description_throws_exception(): void
    {
        // GIVEN
        $whitespaceDescription = '   ';
        $this->expectException(InvalidProductDescriptionException::class);
        $this->expectExceptionMessage(
            'Invalid product description, the length must be between <' . self::MIN_LENGTH . '> and <' . self::MAX_LENGTH . '> characters.',
        );

        // WHEN
        new ProductDescription($whitespaceDescription);
    }

    #[Group('unit')]
    public function test_value_method_returns_correct_value(): void
    {
        // GIVEN
        $expectedValue = self::VALID_DESCRIPTION;
        $productDescription = new ProductDescription($expectedValue);

        // WHEN
        $value = $productDescription->value();

        // THEN
        $this->assertSame($expectedValue, $value);
    }

    #[Group('unit')]
    public function test_trim_whitespace(): void
    {
        // GIVEN
        $descriptionWithWhitespace = '  ' . self::VALID_DESCRIPTION . '  ';
        $expectedValue = trim($descriptionWithWhitespace);

        // WHEN
        $productDescription = new ProductDescription($descriptionWithWhitespace);

        // THEN
        $this->assertSame($expectedValue, $productDescription->value());
    }

    protected function setUp(): void
    {
        parent::setUp();
        // Crear una descripción que exceda el límite máximo
        $this->tooLongDescription = str_repeat('a', self::MAX_LENGTH + 1);
    }
}
