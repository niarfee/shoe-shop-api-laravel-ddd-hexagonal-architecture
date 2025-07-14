<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Src\Shop\Shared\Domain\ValueObject\ProductCategoryId;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Src\Shop\ProductCategory\Infrastructure\Persistence\Eloquent\ProductCategoryEloquentModel\ProductCategory>
 */
class ProductCategoryFactory extends Factory
{
    protected $model = \Src\Shop\ProductCategory\Infrastructure\Persistence\Eloquent\ProductCategoryEloquentModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'id' => ProductCategoryId::generate()->value(),
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
