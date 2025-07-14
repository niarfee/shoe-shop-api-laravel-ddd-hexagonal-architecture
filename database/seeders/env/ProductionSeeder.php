<?php

declare(strict_types=1);

namespace Database\Seeders\Env;

use Database\Seeders\Helpers\ProductCategorySeederHelper;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    public function __construct(
        private ProductCategorySeederHelper $productCategorySeederHelper,
    ) {
    }

    public function run(): void
    {
        // GENERATE

        $productCategoriesData = [
            [
                'id' => '019773a5-3441-7fb8-8f7c-8ba2d103ce06',
                'name' => 'Man',
                'slug' => 'man',
            ],
            [
                'id' => '019773a5-3442-7fee-a6a9-b9d1896e44d0',
                'name' => 'Woman',
                'slug' => 'woman',
            ],
            [
                'id' => '019773a5-3443-7bfd-b958-8348ff4b223d',
                'name' => 'Child',
                'slug' => 'child',
            ],
        ];

        // PERSIST

        $this->productCategorySeederHelper->persistList($productCategoriesData);
    }
}
