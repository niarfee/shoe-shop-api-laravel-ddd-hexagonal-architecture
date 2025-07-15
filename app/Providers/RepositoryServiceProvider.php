<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Shop\Customer\Domain\CustomerRepositoryInterface;
use Src\Shop\Customer\Infrastructure\Repository\EloquentCustomerRepository;
use Src\Shop\Product\Domain\ProductRepositoryInterface;
use Src\Shop\Product\Domain\ProductVariantRepositoryInterface;
use Src\Shop\Product\Infrastructure\Repository\EloquentProductRepository;
use Src\Shop\Product\Infrastructure\Repository\EloquentProductVariantRepository;
use Src\Shop\ProductCategory\Domain\ProductCategoryRepositoryInterface;
use Src\Shop\ProductCategory\Infrastructure\Repository\EloquentProductCategoryRepository;
use Src\Shop\User\Domain\UserRepositoryInterface;
use Src\Shop\User\Infrastructure\Repository\EloquentUserRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CustomerRepositoryInterface::class, EloquentCustomerRepository::class);
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(ProductCategoryRepositoryInterface::class, EloquentProductCategoryRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, EloquentProductRepository::class);
        $this->app->bind(ProductVariantRepositoryInterface::class, EloquentProductVariantRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
