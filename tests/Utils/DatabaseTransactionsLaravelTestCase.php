<?php

declare(strict_types=1);

namespace Tests\Utils;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as LaravelTestCase;

abstract class DatabaseTransactionsLaravelTestCase extends LaravelTestCase
{
    use DatabaseTransactions;

    protected const ID_NOT_EXISTING = '018fe68b-8b6f-73e2-bc82-dfdebc9baf00';

    protected string $connectionLaravel;
    protected string $connectionShop;

    // Required for the DatabaseTransactions trait
    protected function connectionsToTransact()
    {
        $this->connectionLaravel = config('connections.mysql_laravel_connection');
        $this->connectionShop = config('connections.mysql_shop_connection');
        return [
            $this->connectionLaravel,
            $this->connectionShop,
        ];
    }
}
