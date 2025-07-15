<?php

declare(strict_types=1);

namespace Database\Seeders\Env;

use Database\Seeders\Helpers\CustomerSeederHelper;
use Database\Seeders\Helpers\ProductCategorySeederHelper;
use Database\Seeders\Helpers\ProductSeederHelper;
use Database\Seeders\Helpers\UserSeederHelper;
use Illuminate\Database\Seeder;

class StagingSeeder extends Seeder
{
    public function __construct(
        private CustomerSeederHelper $customerSeederHelper,
        private UserSeederHelper $userSeederHelper,
        private ProductCategorySeederHelper $productCategorySeederHelper,
        private ProductSeederHelper $productSeederHelper,
    ) {
    }

    public function run(): void
    {
        // GENERATE

        $customersData = [
            [
                'id' => '01977167-a5f3-782a-aac4-99b0199ae28a',
                'first_name' => 'Roisin',
                'last_name' => 'Burch',
                'email' => 'spencer.ricardo@example.com',
                'created_at' => '2025-06-15 11:38:41',
                'updated_at' => '2025-06-15 11:38:41',
            ],
            [
                'id' => '01977167-a5f3-782a-aac4-99b01a14f0ef',
                'first_name' => 'Harold',
                'last_name' => 'Galvan',
                'email' => 'wolf.sabryna@example.org',
                'created_at' => '2025-06-15 11:38:57',
                'updated_at' => '2025-06-15 11:38:57',
            ],
        ];
        $usersData = [
            [
                'id' => '01977361-afe9-74e2-9e39-a97ebe0a8a99',
                'customer_id' => '01977167-a5f3-782a-aac4-99b0199ae28a',
                'email' => 'spencer.ricardo@example.com',
                'email_verified_at' => '2025-06-15 11:37:51',
                'password' => '$2y$12$iKeB5PCGyky5zoUcIxh9oeGVQQp096zbiclPAt2QVPRwNSVYIItNe', // password
                'remember_token' => '',
                'created_at' => '2025-06-15 11:38:41',
                'updated_at' => '2025-06-15 11:38:41',
            ],
            [
                'id' => '01977362-705b-753e-b72d-508743b60c66',
                'customer_id' => '01977167-a5f3-782a-aac4-99b01a14f0ef',
                'email' => 'wolf.sabryna@example.org',
                'email_verified_at' => '2025-06-15 11:38:40',
                'password' => '$2y$12$iKeB5PCGyky5zoUcIxh9oeGVQQp096zbiclPAt2QVPRwNSVYIItNe', // password
                'remember_token' => '',
                'created_at' => '2025-06-15 11:38:57',
                'updated_at' => '2025-06-15 11:38:57',
            ],
        ];
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
        $productsData = [
            [
                'id' => '019773f3-48e2-7342-8fa4-f6e69984d8a5',
                'product_category_id' => '019773a5-3441-7fb8-8f7c-8ba2d103ce06',
                'name' => 'moccasins 1 Man',
                'description' => 'moccasins 1 Man description',
                'price' => '48.04',
                'created_at' => '2025-06-15 14:16:53',
                'updated_at' => '2025-06-15 14:16:53',
                'variants' => [
                    [
                        'id' => '019773f3-4a3c-7fbf-92f9-700728400aae',
                        'size' => '34',
                        'color' => 'Green',
                        'stock' => '113',
                        'created_at' => '2025-06-15 14:16:53',
                        'updated_at' => '2025-06-15 14:16:53',
                    ],
                    [
                        'id' => '019773f3-4a3d-707a-b434-9f41b59bb7ff',
                        'size' => '36',
                        'color' => 'Red',
                        'stock' => '12',
                        'created_at' => '2025-06-15 14:16:53',
                        'updated_at' => '2025-06-15 14:16:53',
                    ],
                    [
                        'id' => '019773f3-4a3d-707a-b434-9f41b6260311',
                        'size' => '41',
                        'color' => 'Red',
                        'stock' => '34',
                        'created_at' => '2025-06-15 14:16:53',
                        'updated_at' => '2025-06-15 14:16:53',
                    ],
                ],
            ],
            [
                'id' => '01977466-531e-7dc5-bfc3-2639c899969f',
                'product_category_id' => '019773a5-3441-7fb8-8f7c-8ba2d103ce06',
                'name' => 'boots 2 Man',
                'description' => 'boots 2 Man description',
                'price' => '75.52',
                'created_at' => '2025-06-15 14:16:53',
                'updated_at' => '2025-06-15 14:16:53',
                'variants' => [
                    [
                        'id' => '019773f3-4a3e-7a8d-8617-a918f2f020d9',
                        'size' => '44',
                        'color' => 'Green',
                        'stock' => '72',
                        'created_at' => '2025-06-15 14:16:53',
                        'updated_at' => '2025-06-15 14:16:53',
                    ],
                    [
                        'id' => '019773f3-4a3e-7a8d-8617-a918f3648505',
                        'size' => '45',
                        'color' => 'Black',
                        'stock' => '149',
                        'created_at' => '2025-06-15 14:16:53',
                        'updated_at' => '2025-06-15 14:16:53',
                    ],
                ],
            ],
            [
                'id' => '019773f3-4a3f-7d27-bbe1-af53fc17d022',
                'product_category_id' => '019773a5-3442-7fee-a6a9-b9d1896e44d0',
                'name' => 'sandals 1 Woman',
                'description' => 'sandals 1 Woman description',
                'price' => '28.46',
                'created_at' => '2025-06-15 14:16:53',
                'updated_at' => '2025-06-15 14:16:53',
                'variants' => [
                    [
                        'id' => '019773f3-4a3f-7d27-bbe1-af53fcd076fc',
                        'size' => '35',
                        'color' => 'Ocre',
                        'stock' => '55',
                        'created_at' => '2025-06-15 14:16:53',
                        'updated_at' => '2025-06-15 14:16:53',
                    ],
                ],
            ],
            [
                'id' => '019773f3-4a40-7b7a-876c-ed59d6190594',
                'product_category_id' => '019773a5-3442-7fee-a6a9-b9d1896e44d0',
                'name' => 'sneakers 2 Woman',
                'description' => 'sneakers 2 Woman description',
                'price' => '67.37',
                'created_at' => '2025-06-15 14:16:53',
                'updated_at' => '2025-06-15 14:16:53',
                'variants' => [
                    [
                        'id' => '019773f3-4a40-7b7a-876c-ed59d6e3f5c9',
                        'size' => '36',
                        'color' => 'White',
                        'stock' => '28',
                        'created_at' => '2025-06-15 14:16:53',
                        'updated_at' => '2025-06-15 14:16:53',
                    ],
                    [
                        'id' => '019773f3-4a41-71e2-a423-d5e0645bca5f',
                        'size' => '37',
                        'color' => 'Blue',
                        'stock' => '12',
                        'created_at' => '2025-06-15 14:16:53',
                        'updated_at' => '2025-06-15 14:16:53',
                    ],
                ],
            ],
            [
                'id' => '019773f3-4a41-71e2-a423-d5e064e93efb',
                'product_category_id' => '019773a5-3443-7bfd-b958-8348ff4b223d',
                'name' => 'boots 1 Child',
                'description' => 'boots 1 Child description',
                'price' => '6.25',
                'created_at' => '2025-06-15 14:16:53',
                'updated_at' => '2025-06-15 14:16:53',
                'variants' => [
                    [
                        'id' => '019773f3-4a41-71e2-a423-d5e065552181',
                        'size' => '34',
                        'color' => 'Red',
                        'stock' => '17',
                        'created_at' => '2025-06-15 14:16:53',
                        'updated_at' => '2025-06-15 14:16:53',
                    ],
                    [
                        'id' => '019773f3-4a42-724e-90b1-a29ac2346e28',
                        'size' => '36',
                        'color' => 'Green',
                        'stock' => '101',
                        'created_at' => '2025-06-15 14:16:53',
                        'updated_at' => '2025-06-15 14:16:53',
                    ],
                    [
                        'id' => '019773f3-4a42-724e-90b1-a29ac2966507',
                        'size' => '37',
                        'color' => 'Blue',
                        'stock' => '63',
                        'created_at' => '2025-06-15 14:16:53',
                        'updated_at' => '2025-06-15 14:16:53',
                    ],
                ],
            ],
            [
                'id' => '019773f3-4a42-724e-90b1-a29ac2f5a9ab',
                'product_category_id' => '019773a5-3443-7bfd-b958-8348ff4b223d',
                'name' => 'sandals 5 Child',
                'description' => 'sandals 5 Child description',
                'price' => '71.87',
                'created_at' => '2025-06-15 14:16:53',
                'updated_at' => '2025-06-15 14:16:53',
                'variants' => [
                    [
                        'id' => '019773f3-4a43-7518-89ba-70c6bbe42dfd',
                        'size' => '34',
                        'color' => 'Black',
                        'stock' => '45',
                        'created_at' => '2025-06-15 14:16:53',
                        'updated_at' => '2025-06-15 14:16:53',
                    ],
                ],
            ],
        ];

        // PERSIST

        $this->customerSeederHelper->persistList($customersData);
        $this->userSeederHelper->persistList($usersData);
        $this->productCategorySeederHelper->persistList($productCategoriesData);
        $this->productSeederHelper->persistList($productsData);
    }
}
