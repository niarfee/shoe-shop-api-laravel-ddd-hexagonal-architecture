<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Product\Domain;

use PHPUnit\Framework\Attributes\Group;
use Src\Shop\Product\Domain\Exception\ProductAlreadyContainsProductVariantForSizeAndColorException;
use Src\Shop\Product\Domain\Exception\ProductVariantDoesNotBelongToProductException;
use Src\Shop\Product\Domain\Exception\ProductVariantNotFoundException;
use Src\Shop\Product\Domain\ValueObject\ProductId;
use Src\Shop\Product\Domain\ValueObject\ProductVariantColor;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantId;
use Tests\Src\Shop\Product\Domain\ValueObject\ProductVariantSizeMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\ProductVariantIdMother;
use Tests\Utils\UnitPhpUnitTestCase;

final class ProductTest extends UnitPhpUnitTestCase
{
    #[Group('unit')]
    public function test_it_throws_exception_when_adding_variant_from_different_product(): void
    {
        // GIVEN
        $productId = ProductId::generate();
        $otherProductId = ProductId::generate();

        $productVariant = ProductVariantMother::make(
            ProductVariantId::generate(),
            $otherProductId,
        );

        $product = ProductMother::make($productId);

        // THEN
        $this->expectException(ProductVariantDoesNotBelongToProductException::class);
        $this->expectExceptionMessage(
            'OrderLine <' . $productVariant->id()->value() . '> with OrderId <' .
            $otherProductId->value() . '> does not belong to Order with OrderId <' .
            $productId->value() . '>.',
        );

        // WHEN
        $product->addVariant($productVariant);
    }

    #[Group('unit')]
    public function test_it_throws_exception_when_variant_not_found(): void
    {
        // GIVEN
        $product = ProductMother::make();
        $nonExistentVariantId = ProductVariantIdMother::make();

        // THEN
        $this->expectException(ProductVariantNotFoundException::class);
        $this->expectExceptionMessage(
            'Product variant <' . $nonExistentVariantId->value() . '> not found.',
        );

        // WHEN
        $product->variantById($nonExistentVariantId);
    }

    #[Group('unit')]
    public function test_it_throws_exception_when_adding_duplicate_variant_size_and_color(): void
    {
        // GIVEN
        $product = ProductMother::make();
        $variant = ProductVariantMother::make(
            productId: $product->id(),
            size: ProductVariantSizeMother::make(39),
            color: ProductVariantColor::createRed(),
        );

        // Add the first variant
        $product->addVariant($variant);

        // Create another variant with same size and color but different ID
        $duplicateVariant = ProductVariantMother::make(
            productId: $product->id(),
            size: $variant->size(),
            color: $variant->color(),
        );

        // THEN
        $this->expectException(ProductAlreadyContainsProductVariantForSizeAndColorException::class);
        $this->expectExceptionMessage(
            'Product <' . $product->id()->value() . '> already contains a variant for size <39> and color <Red>.',
        );

        // WHEN
        $product->addVariant($duplicateVariant);
    }
}
