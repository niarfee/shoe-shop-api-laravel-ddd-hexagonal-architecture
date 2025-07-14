<?php

declare(strict_types=1);

namespace Database\Seeders\Env;

use Database\Seeders\Helpers\CustomerSeederHelper;
use Database\Seeders\Helpers\ProductCategorySeederHelper;
use Database\Seeders\Helpers\UserSeederHelper;
use Illuminate\Database\Seeder;

class LocalSeeder extends Seeder
{
    public function __construct(
        private CustomerSeederHelper $customerSeederHelper,
        private UserSeederHelper $userSeederHelper,
        private ProductCategorySeederHelper $productCategorySeederHelper,
    ) {
    }

    public function run(): void
    {
        // GENERATE

        $productCategories = $this->productCategorySeederHelper->buildFake(3);
        $productCategoriesData = $this->productCategorySeederHelper->fromDomainListToArrayList($productCategories);

        // PERSIST

        $this->productCategorySeederHelper->persistList($productCategoriesData);
    }
}
