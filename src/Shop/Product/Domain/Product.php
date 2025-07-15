<?php

declare(strict_types=1);

namespace Src\Shop\Product\Domain;

use Src\Shop\Product\Domain\Exception\ProductAlreadyContainsProductVariantForSizeAndColorException;
use Src\Shop\Product\Domain\Exception\ProductVariantColorDoesNotMatchException;
use Src\Shop\Product\Domain\Exception\ProductVariantDoesNotBelongToProductException;
use Src\Shop\Product\Domain\Exception\ProductVariantNotFoundException;
use Src\Shop\Product\Domain\Exception\ProductVariantSizeDoesNotMatchException;
use Src\Shop\Product\Domain\ValueObject\ProductDescription;
use Src\Shop\Product\Domain\ValueObject\ProductId;
use Src\Shop\Product\Domain\ValueObject\ProductName;
use Src\Shop\Product\Domain\ValueObject\ProductPrice;
use Src\Shop\Product\Domain\ValueObject\ProductVariantColor;
use Src\Shop\Product\Domain\ValueObject\ProductVariantSize;
use Src\Shop\Shared\Domain\ValueObject\OrderLineUnits;
use Src\Shop\Shared\Domain\ValueObject\ProductCategoryId;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantId;

final class Product
{
    /**
     * @param ProductVariant[] $variants
     */
    private array $variants = [];

    private function __construct(
        private readonly ProductId $id,
        private readonly ProductCategoryId $productCategoryId,
        private readonly ProductName $name,
        private readonly ProductDescription $description,
        private readonly ProductPrice $price,
    ) {
    }

    public static function create(
        ProductId $id,
        ProductCategoryId $productCategoryId,
        ProductName $name,
        ProductDescription $description,
        ProductPrice $price,
        array $variants = [],
    ): self {
        $product = new self($id, $productCategoryId, $name, $description, $price);
        $product->addVariants($variants);
        return $product;
    }

    // Public state mutation

    /**
     * @param ProductVariant[] $variants
     */
    public function addVariants(array $variants): void
    {
        foreach ($variants as $variant) {
            $this->addVariant($variant);
        }
    }

    public function addVariant(ProductVariant $productVariant): void
    {
        $this->ensureBelongsToProduct($productVariant);
        $this->ensureNotHasVariant($productVariant->size(), $productVariant->color());

        $this->variants[] = $productVariant;
    }

    public function updateVariantDecrementStock(
        ProductVariantId $productVariantId,
        OrderLineUnits $orderLineUnits,
    ): ?ProductVariant {
        $productVariant = $this->variantById($productVariantId);

        $productVariant->decrementStock($orderLineUnits);
        $this->updateVariant($productVariant);

        return $productVariant;
    }

    // Public query methods

    public function variantById(ProductVariantId $productVariantId): ProductVariant
    {
        foreach ($this->variants as $variant) {
            if ($variant->id()->value() === $productVariantId->value()) {
                return $variant;
            }
        }
        throw new ProductVariantNotFoundException($productVariantId);
    }

    public function hasVariant(
        ProductVariantSize $size,
        ProductVariantColor $color,
    ): bool {
        return $this->variantBySizeAndColor($size, $color) !== null;
    }

    // Public accessors

    public function variants(): array
    {
        return $this->variants;
    }

    public function id(): ProductId
    {
        return $this->id;
    }

    public function productCategoryId(): ProductCategoryId
    {
        return $this->productCategoryId;
    }

    public function name(): ProductName
    {
        return $this->name;
    }

    public function description(): ProductDescription
    {
        return $this->description;
    }

    public function price(): ProductPrice
    {
        return $this->price;
    }

    // Private state mutation

    private function updateVariant(ProductVariant $productVariantToUpdate): void
    {
        $this->ensureBelongsToProduct($productVariantToUpdate);

        foreach ($this->variants as $index => $variant) {
            if ($variant->id()->equals($productVariantToUpdate->id())) {
                $this->ensureVariantUniqueness($variant, $productVariantToUpdate);
                $this->variants[$index] = $productVariantToUpdate;
            }
        }
    }

    // Private query methods

    private function variantBySizeAndColor(
        ProductVariantSize $size,
        ProductVariantColor $color,
    ): ?ProductVariant {
        foreach ($this->variants as $variant) {
            if (
                $variant->size()->value() === $size->value()
                && $variant->color()->value() === $color->value()
            ) {
                return $variant;
            }
        }
        return null;
    }

    // Private guard clauses

    /**
     * Ensures variant consistency during updates.
     *
     * @note Exceptions are unreachable in current design but kept for robustness.
     *
     * @param ProductVariant $currentProductVariant The existing product variant
     * @param ProductVariant $otherProductVariant The product variant with potential updates
     *
     * @throws ProductVariantSizeDoesNotMatchException If the size doesn't match (unreachable in current design)
     * @throws ProductVariantColorDoesNotMatchException If the color doesn't match (unreachable in current design)
     */
    private function ensureVariantUniqueness(ProductVariant $currentProductVariant, ProductVariant $otherProductVariant): void
    {
        if (!$currentProductVariant->size()->equals($otherProductVariant->size())) {
            throw new ProductVariantSizeDoesNotMatchException(
                $currentProductVariant->id(),
                $currentProductVariant->size(),
                $otherProductVariant->size(),
            );
        }
        if (!$currentProductVariant->color()->equals($otherProductVariant->color())) {
            throw new ProductVariantColorDoesNotMatchException(
                $currentProductVariant->id(),
                $currentProductVariant->color(),
                $otherProductVariant->color(),
            );
        }
    }

    private function ensureBelongsToProduct(ProductVariant $productVariant): void
    {
        if (!$productVariant->belongsToProduct($this->id)) {
            throw new ProductVariantDoesNotBelongToProductException(
                $productVariant->id(),
                $productVariant->productId(),
                $this->id,
            );
        }
    }

    private function ensureNotHasVariant(ProductVariantSize $productVariantSize, ProductVariantColor $productVariantColor): void
    {
        if ($this->hasVariant($productVariantSize, $productVariantColor)) {
            throw new ProductAlreadyContainsProductVariantForSizeAndColorException(
                $this->id,
                $productVariantSize,
                $productVariantColor,
            );
        }
    }
}
