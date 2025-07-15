<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Utils;

final class MoneyUtils
{
    private const SCALE = 2;

    public static function multiply(float $amount, int $multiplier): float
    {
        return (float) bcmul((string) $amount, (string) $multiplier, self::SCALE);
    }
}
