<?php

declare(strict_types=1);

namespace Src\Shared\Infrastructure\Persistence\Eloquent;

use Illuminate\Support\Facades\DB;
use Src\Shared\Domain\TransactionalInterface;
use Throwable;

final class LaravelMultiConnectionTransactional implements TransactionalInterface
{
    private array $connections;

    public function __construct(array $connections)
    {
        $this->connections = $connections;
    }

    public function run(callable $callback): mixed
    {
        foreach ($this->connections as $conn) {
            DB::connection($conn)->beginTransaction();
        }

        try {
            $result = $callback();

            foreach ($this->connections as $conn) {
                DB::connection($conn)->commit();
            }

            return $result;
        } catch (Throwable $e) {
            foreach ($this->connections as $conn) {
                DB::connection($conn)->rollBack();
            }
            throw $e;
        }
    }
}
