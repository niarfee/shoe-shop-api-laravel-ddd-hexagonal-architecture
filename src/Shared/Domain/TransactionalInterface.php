<?php

declare(strict_types=1);

namespace Src\Shared\Domain;

interface TransactionalInterface
{
    public function run(callable $callback): mixed;
}
