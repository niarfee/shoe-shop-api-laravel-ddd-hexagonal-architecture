<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Src\Shop\Product\Domain\ValueObject\ProductVariantColor;
use Src\Shop\Product\Domain\ValueObject\ProductVariantSize;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantId;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductVariantFactory extends Factory
{
    protected $model = \Src\Shop\Product\Infrastructure\Persistence\Eloquent\ProductVariantEloquentModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => ProductVariantId::generate()->value(),
            'product_id' => null,
            'size' => fake()->randomElement(ProductVariantSize::sizesAvailable()),
            'color' => fake()->randomElement(ProductVariantColor::colorNamesAvailable()),
            'stock' => fake()->numberBetween(0, 200),
        ];
    }
}
