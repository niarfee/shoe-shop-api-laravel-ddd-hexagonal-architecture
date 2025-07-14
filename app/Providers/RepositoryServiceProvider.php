<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Shop\ProductCategory\Domain\ProductCategoryRepositoryInterface;
use Src\Shop\ProductCategory\Infrastructure\Repository\EloquentProductCategoryRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProductCategoryRepositoryInterface::class, EloquentProductCategoryRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
