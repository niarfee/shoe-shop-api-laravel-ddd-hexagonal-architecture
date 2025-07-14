<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Shared\Domain\PasswordValidatorInterface;
use Src\Shared\Domain\TransactionalInterface;
use Src\Shared\Infrastructure\Persistence\Eloquent\LaravelMultiConnectionTransactional;
use Src\Shared\Infrastructure\Service\PasswordValidator;

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
        $this->app->bind(
            PasswordValidatorInterface::class,
            PasswordValidator::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
