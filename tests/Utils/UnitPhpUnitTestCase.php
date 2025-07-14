<?php

declare(strict_types=1);

namespace Tests\Utils;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class UnitPhpUnitTestCase extends PHPUnitTestCase
{
    protected const VALID_UUID = '123e4567-e89b-12d3-a456-426614174000';
    protected const INVALID_UUID = 'not-a-valid-uuid';
}
