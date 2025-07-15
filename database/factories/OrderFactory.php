<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Src\Shop\Order\Domain\ValueObject\OrderId;
use Src\Shop\Order\Domain\ValueObject\OrderStatus;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrderFactory extends Factory
{
    protected $model = \Src\Shop\Order\Infrastructure\Persistence\Eloquent\OrderEloquentModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => OrderId::generate()->value(),
            'customer_id' => null,
            'status' => fake()->randomElement(OrderStatus::statusValuesAvailable()),
            'total_price' => 0,
        ];
    }
}
