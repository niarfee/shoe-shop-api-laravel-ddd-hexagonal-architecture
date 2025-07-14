<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Customer\Domain\ValueObject;

use Src\Shop\Customer\Domain\ValueObject\CustomerLastName;
use Tests\Src\Shop\Shared\Domain\MotherCreator;

final class CustomerLastNameMother
{
    public static function make(?string $value = null): CustomerLastName
    {
        return new CustomerLastName($value ?? MotherCreator::faker()->lastName);
    }
}
