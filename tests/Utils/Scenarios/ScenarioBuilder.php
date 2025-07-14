<?php

declare(strict_types=1);

namespace Tests\Utils\Scenarios;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

abstract class ScenarioBuilder
{
    protected static function truncateTable(string $eloquentModelClassName): void
    {
        if (!is_subclass_of($eloquentModelClassName, Model::class)) {
            throw new InvalidArgumentException(
                "Class $eloquentModelClassName must extend Illuminate\\Database\\Eloquent\\Model",
            );
        }

        DB::connection(config('connections.mysql_laravel_connection'))->statement('SET FOREIGN_KEY_CHECKS=0');
        DB::connection(config('connections.mysql_laravel_connection'))->statement('SET FOREIGN_KEY_CHECKS=0');

        // Does not work with DatabaseTransactions
        // $eloquentModelClassName::truncate();

        // It does work with DatabaseTransactions
        $eloquentModelClassName::query()->delete();

        DB::connection(config('connections.mysql_laravel_connection'))->statement('SET FOREIGN_KEY_CHECKS=1');
        DB::connection(config('connections.mysql_laravel_connection'))->statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
