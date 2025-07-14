<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Utils;

final class StringUtils
{
    public static function containsSpecialCharacters(string $value): bool
    {
        return preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $value) === 1;
    }

    public static function containsSpecialCharactersExceptUnderscore(string $value): bool
    {
        return preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $value) === 1;
    }
}
