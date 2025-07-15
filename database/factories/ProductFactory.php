<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Src\Shop\Product\Domain\ValueObject\ProductId;
use Src\Shop\Product\Infrastructure\Persistence\Eloquent\ProductEloquentModel;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductFactory extends Factory
{
    protected $model = ProductEloquentModel::class;

    private static array $productNames = [
        'crocs',
        'moccasins',
        'safety shoes',
        'ballet flats',
        'rollerblades',
        'chelsea boots',
        'trainers',
        'gladiator sandals',
        'snowshoes',
        'pointe shoes',
        'loafers',
        'ankle boots',
        'cycling shoes',
        'oxfords',
        'court shoes',
        'boat shoes',
        'combat boots',
        'platform shoes',
        'brogues',
        'mary janes',
        'running shoes',
        'roller skates',
        'espadrilles',
        'walking shoes',
        'jazz shoes',
        'monk strap shoes',
        'derby shoes',
        'tap shoes',
        'peep-toe shoes',
        'flip-flops',
        'block heels',
        'slides',
        'barefoot shoes',
        'skate shoes',
        'dress shoes',
        'work shoes',
        'ballet shoes',
        'character shoes',
        'stilettos',
        'toe shoes',
        'soccer cleats',
        'chukka boots',
        'wellington boots',
        'kitten heels',
        'tassel loafers',
        'sandals',
        'desert boots',
        'hiking boots',
        'mules',
        'driving loafers',
        'house shoes',
        'sneakers',
        'high heels',
        'dress sandals',
        'climbing shoes',
        'cowboy boots',
        'golf shoes',
        'clogs',
        'penny loafers',
        'water shoes',
        'rain boots',
        'platform sandals',
        'ice skates',
        'basketball shoes',
        'work boots',
        'wedge sandals',
        'slingbacks',
        'wedge heels',
        'snow boots',
        'pumps',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomName = fake()->randomElement(self::$productNames) . ' ' . fake()->randomNumber(2);

        return [
            'id' => ProductId::generate()->value(),
            'product_category_id' => null,
            'name' => $randomName,
            'description' => $randomName . ' description',
            'price' => fake()->randomFloat(2, 5, 80),
        ];
    }

    // public function withVariants(array $variants): static
    // {
    //     return $this->afterCreating(function (ProductEloquentModel $product) use ($variants) {
    //         foreach ($variants as $variant) {
    //             $product->variants()->create([
    //                 ...$variant,
    //                 'product_id' => $product->getKey(),
    //             ]);
    //         }
    //     });
    // }
}
