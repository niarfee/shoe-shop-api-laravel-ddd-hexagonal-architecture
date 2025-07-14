<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Customer\Domain\ValueObject;

use Src\Shop\Customer\Domain\ValueObject\CustomerEmail;
use Tests\Src\Shop\Shared\Domain\MotherCreator;

final class CustomerEmailMother
{
    public static function make(?string $value = null): CustomerEmail
    {
        return new CustomerEmail($value ?? MotherCreator::faker()->unique()->safeEmail);
    }
}
