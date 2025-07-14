<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Customer\Domain\ValueObject;

use Src\Shop\Customer\Domain\ValueObject\CustomerFirstName;
use Tests\Src\Shop\Shared\Domain\MotherCreator;

final class CustomerFirstNameMother
{
    public static function make(?string $value = null): CustomerFirstName
    {
        return new CustomerFirstName($value ?? MotherCreator::faker()->firstName);
    }
}
