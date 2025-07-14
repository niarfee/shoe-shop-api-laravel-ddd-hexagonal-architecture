<?php

declare(strict_types=1);

namespace Database\Seeders\Env;

use Illuminate\Database\Seeder;

class TestingSeeder extends Seeder
{
    public function run(): void
    {
        // We do not need seeders for testing as we are using the DatabaseTransactions trais.
    }
}
