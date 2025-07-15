<?php

declare(strict_types=1);

namespace Database\Seeders\Env;

use Database\Seeders\Helpers\CustomerSeederHelper;
use Database\Seeders\Helpers\OrderSeederHelper;
use Database\Seeders\Helpers\ProductCategorySeederHelper;
use Database\Seeders\Helpers\ProductSeederHelper;
use Database\Seeders\Helpers\UserSeederHelper;
use Illuminate\Database\Seeder;

class LocalSeeder extends Seeder
{
    public function __construct(
        private CustomerSeederHelper $customerSeederHelper,
        private UserSeederHelper $userSeederHelper,
        private ProductCategorySeederHelper $productCategorySeederHelper,
        private ProductSeederHelper $productSeederHelper,
        private OrderSeederHelper $orderSeederHelper,
    ) {
    }

    public function run(): void
    {
        // GENERATE

        $customers = $this->customerSeederHelper->buildFake(2);
        $users = $this->userSeederHelper->buildFake($customers);
        $productCategories = $this->productCategorySeederHelper->buildFake(3);
        $products = $this->productSeederHelper->buildFake(
            productCategories: $productCategories,
            quantityProducts: 20,
            quantityRandomProductsVariants: [2, 3, 4, 5],
        );
        $orders = $this->orderSeederHelper->buildFake(
            customers: $customers,
            products: $products,
            quantityOrders: 3,
            quantityRandomOrderLines: [1, 2, 3],
        );

        $customersData = $this->customerSeederHelper->fromDomainListToArrayList($customers);
        $usersData = $this->userSeederHelper->fromDomainListToArrayList($users);
        $productCategoriesData = $this->productCategorySeederHelper->fromDomainListToArrayList($productCategories);
        $productsData = $this->productSeederHelper->fromDomainListToArrayList($products);
        $ordersData = $this->orderSeederHelper->fromDomainListToArrayList($orders);

        // PERSIST

        $this->customerSeederHelper->persistList($customersData);
        $this->userSeederHelper->persistList($usersData);
        $this->productCategorySeederHelper->persistList($productCategoriesData);
        $this->productSeederHelper->persistList($productsData);
        $this->orderSeederHelper->persistList($ordersData);
    }
}
