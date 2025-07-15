<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Src\Shop\Order\Domain\ValueObject\OrderLineId;
use Src\Shop\Product\Infrastructure\Persistence\Eloquent\ProductVariantEloquentModel;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantStock;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantStockRequested;
use Src\Shop\Shared\Domain\ValueObject\StockRequest;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrderLineFactory extends Factory
{
    protected $model = \Src\Shop\Order\Infrastructure\Persistence\Eloquent\OrderLineEloquentModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => OrderLineId::generate()->value(),
            'order_id' => null,
            'product_variant_id' => null,
            'units' => function (array $attributes) {
                $variant = $this->getProductVariantOrFail($attributes, ['stock']);
                $requestedUnits = fake()->numberBetween(1, 99);
                return $this->calculateUnits($requestedUnits, $variant->stock);
            },
            'unit_price' => function (array $attributes) {
                $variant = $this->getProductVariantOrFail($attributes, ['product']);
                return $variant->product->price;
            },
        ];
    }

    // public function withCalculatedValuesFromProductVariant(string $productVariantId, int $productVariantStock, float $unitPrice, ?int $requestedUnits = null): self
    // {
    //     $requestedUnits ??= fake()->numberBetween(1, 99);

    //     $units = $this->calculateUnits($requestedUnits, $productVariantStock);

    //     return $this->state(fn() => [
    //         'product_variant_id' => $productVariantId,
    //         'units' => $units,
    //         'unit_price' => $unitPrice,
    //     ]);
    // }

    private function calculateUnits(int $requestedUnits, int $productVariantStock): int
    {
        return StockRequest::create(
            new ProductVariantStockRequested($requestedUnits),
            new ProductVariantStock($productVariantStock),
        )->toOrderLineUnits()->value();
    }

    /**
     * @param array $attributes
     * @param array $with Relationships to load (ex: ['product'])
     * @throws \RuntimeException if the ProductVariant is not found or the ProductVariant ID is missing
     * @returnProductVariantEloquentModel
     */
    private function getProductVariantOrFail(array $attributes, array $with = []): ProductVariantEloquentModel
    {
        // $msgSuffix = "Use the withCalculatedValuesFromProductVariant method if there is no ProductVariant in the repository.";
        $msgSuffix = '';
        if (empty($attributes['product_variant_id'])) {
            throw new \RuntimeException("'product_variant_id' is required in the OrderLineFactory. $msgSuffix");
        }

        $query = ProductVariantEloquentModel::query();
        if (!empty($with)) {
            $query->with($with);
        }

        $variant = $query->find($attributes['product_variant_id']);

        if (!$variant) {
            throw new \RuntimeException("ProductVariant not found with ID: {$attributes['product_variant_id']} $msgSuffix");
        }

        return $variant;
    }
}
