<?php

declare(strict_types=1);

namespace Tests\Shop\Product\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Group;
use Src\Shop\Product\Domain\Enum\ProductVariantColorEnum;
use Src\Shop\Product\Domain\Exception\InvalidProductVariantColorException;
use Src\Shop\Product\Domain\ValueObject\ProductVariantColor;
use Tests\Utils\UnitPhpUnitTestCase;

final class ProductVariantColorTest extends UnitPhpUnitTestCase
{
    #[Group('unit')]
    public function test_create_valid_color_using_named_constructor(): void
    {
        // GIVEN
        $validColor = ProductVariantColorEnum::Red->value;

        // WHEN
        $color = ProductVariantColor::create($validColor);

        // THEN
        $this->assertSame($validColor, $color->value());
    }

    #[Group('unit')]
    public function test_create_invalid_color_throws_exception(): void
    {
        // GIVEN
        $invalidColor = 'invalid-color';
        $this->expectException(InvalidProductVariantColorException::class);
        $this->expectExceptionMessage('Invalid product variant color');

        // WHEN
        ProductVariantColor::create($invalidColor);
    }

    #[Group('unit')]
    public function test_create_static_methods(): void
    {
        // GIVEN
        $testCases = [
            'createGreen' => ProductVariantColorEnum::Green->value,
            'createRed' => ProductVariantColorEnum::Red->value,
            'createWhite' => ProductVariantColorEnum::White->value,
            'createBlack' => ProductVariantColorEnum::Black->value,
            'createBlue' => ProductVariantColorEnum::Blue->value,
            'createOcre' => ProductVariantColorEnum::Ocre->value,
        ];

        foreach ($testCases as $method => $expectedValue) {
            // WHEN
            $color = ProductVariantColor::$method();

            // THEN
            $this->assertSame($expectedValue, $color->value());
        }
    }

    #[Group('unit')]
    public function test_color_names_available(): void
    {
        // GIVEN
        $expectedColors = array_map(
            fn (ProductVariantColorEnum $color) => $color->value,
            ProductVariantColorEnum::cases(),
        );

        // WHEN
        $availableColors = ProductVariantColor::colorNamesAvailable();

        // THEN
        $this->assertSame($expectedColors, $availableColors);
    }

    #[Group('unit')]
    public function test_hex_code(): void
    {
        // GIVEN
        $color = ProductVariantColor::createRed();
        $expectedHex = ProductVariantColorEnum::Red->hexCode();

        // WHEN
        $hexCode = $color->hexCode();

        // THEN
        $this->assertSame($expectedHex, $hexCode);
    }

    #[Group('unit')]
    public function test_equals(): void
    {
        // GIVEN
        $color1 = ProductVariantColor::createRed();
        $color2 = ProductVariantColor::createRed();
        $color3 = ProductVariantColor::createBlue();

        // THEN
        $this->assertTrue($color1->equals($color2));
        $this->assertFalse($color1->equals($color3));
    }

    #[Group('unit')]
    public function test_value_method_returns_correct_value(): void
    {
        // GIVEN
        $expectedValue = ProductVariantColorEnum::Red->value;
        $color = ProductVariantColor::createRed();

        // WHEN
        $value = $color->value();

        // THEN
        $this->assertSame($expectedValue, $value);
    }
}
