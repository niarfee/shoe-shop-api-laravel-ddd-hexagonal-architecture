<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Shared\Domain;

use Faker\Factory;
use Faker\Generator;

final class MotherCreator
{
    private static ?Generator $faker = null;

    public static function faker(): Generator
    {
        if (self::$faker === null) {
            self::$faker = Factory::create();
        }

        return self::$faker;
    }
}
