<?php

declare(strict_types=1);

namespace Src\Shop\Product\Domain;

use Src\Shop\Product\Domain\ValueObject\ProductId;
use Src\Shop\Product\Domain\ValueObject\ProductVariantColor;
use Src\Shop\Product\Domain\ValueObject\ProductVariantSize;
use Src\Shop\Shared\Domain\ValueObject\OrderLineUnits;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantId;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantStock;

final class ProductVariant
{
    private function __construct(
        private readonly ProductVariantId $id,
        private readonly ProductId $productId,
        private readonly ProductVariantSize $size,
        private readonly ProductVariantColor $color,
        private ProductVariantStock $stock,
    ) {
    }

    public static function create(ProductVariantId $id, ProductId $productId, ProductVariantSize $size, ProductVariantColor $color, ProductVariantStock $stock): self
    {
        $productVariant = new self($id, $productId, $size, $color, $stock);
        return $productVariant;
    }

    // Public state mutation

    public function decrementStock(OrderLineUnits $stockToDecrement): void
    {
        $this->stock = new ProductVariantStock($this->stock->value() - $stockToDecrement->value());
    }

    // Public query methods

    public function belongsToProduct(ProductId $productId): bool
    {
        return $this->productId()->equals($productId);
    }

    // Public accessors

    public function id(): ProductVariantId
    {
        return $this->id;
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function size(): ProductVariantSize
    {
        return $this->size;
    }

    public function color(): ProductVariantColor
    {
        return $this->color;
    }

    public function stock(): ProductVariantStock
    {
        return $this->stock;
    }
}
