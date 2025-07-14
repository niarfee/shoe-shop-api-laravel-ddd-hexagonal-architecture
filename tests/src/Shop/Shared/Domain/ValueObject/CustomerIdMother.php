<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Shared\Domain\ValueObject;

use Src\Shop\Shared\Domain\ValueObject\CustomerId;

final class CustomerIdMother
{
    public static function make(?string $value = null): CustomerId
    {
        return new CustomerId($value ?? CustomerId::generate()->value());
    }
}
