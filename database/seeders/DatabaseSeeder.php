<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $env = app()->environment();

        match ($env) {
            'production' => $this->call(\Database\Seeders\Env\ProductionSeeder::class),
            'staging'    => $this->call(\Database\Seeders\Env\StagingSeeder::class),
            'local'      => $this->call(\Database\Seeders\Env\LocalSeeder::class),
            'testing'    => throw new RuntimeException('We do not need seeders for testing as we are using the DatabaseTransactions trais.'),
            'default' => throw new RuntimeException("Unknown environment '$env'."),
        };
    }
}
