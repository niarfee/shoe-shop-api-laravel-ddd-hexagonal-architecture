<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Make it look for the Factory in the default directory "Database\Factories"
        // instead of "Database\Factories\Src\Shop\****\Infrastructure\Persistence\Eloquent\****Factory".
        Factory::guessFactoryNamesUsing(function (string $model_name) {
            $namespace = 'Database\\Factories\\';
            $model_name = Str::remove('EloquentModel', Str::afterLast($model_name, '\\'));
            return $namespace . $model_name . 'Factory';
        });
    }
}
