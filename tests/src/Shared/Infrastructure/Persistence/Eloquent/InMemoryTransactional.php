<?php

declare(strict_types=1);

namespace Tests\Src\Shared\Infrastructure\Persistence\Eloquent;

use Src\Shared\Domain\TransactionalInterface;

final class InMemoryTransactional implements TransactionalInterface
{
    public function run(callable $callback): mixed
    {
        return $callback();
    }
}
