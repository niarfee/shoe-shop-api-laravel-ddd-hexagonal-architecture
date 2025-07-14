<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Src\Shop\Customer\Infrastructure\Persistence\Eloquent\CustomerEloquentModel;
use Src\Shop\Shared\Domain\ValueObject\CustomerId;
use Src\Shop\User\Infrastructure\Persistence\Eloquent\UserEloquentModel;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Src\Shop\Customer\Infrastructure\Persistence\Eloquent\CustomerEloquentModel\Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = \Src\Shop\Customer\Infrastructure\Persistence\Eloquent\CustomerEloquentModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => CustomerId::generate()->value(),
            'first_name' => fake()->name(),
            'last_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
        ];
    }

    public function withUser(): static
    {
        return $this->afterCreating(function (CustomerEloquentModel $customerEloquentModel) {
            UserEloquentModel::factory()->create([
                'customer_id' => $customerEloquentModel->id,
                'email' => $customerEloquentModel->email,
            ]);
        });
    }
}
