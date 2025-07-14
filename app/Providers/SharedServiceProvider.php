<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Shared\Domain\TransactionalInterface;
use Src\Shared\Infrastructure\Persistence\Eloquent\LaravelMultiConnectionTransactional;

class SharedServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TransactionalInterface::class, function () {
            return new LaravelMultiConnectionTransactional([
                config('connections.mysql_laravel_connection'),
                config('connections.mysql_shop_connection'),
            ]);
        });
    }

    public function boot(): void
    {
        //
    }
}
