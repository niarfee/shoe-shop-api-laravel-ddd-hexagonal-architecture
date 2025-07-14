<?php

declare(strict_types=1);

namespace Src\Shared\Infrastructure\Service;

use Src\Shared\Domain\Exception\InvalidPasswordException;
use Src\Shared\Domain\Exception\PasswordConfirmationDoesNotMatchException;
use Src\Shared\Domain\PasswordValidatorInterface;
use Src\Shared\Domain\Utils\StringUtils;

final class PasswordValidator implements PasswordValidatorInterface
{
    private const int MIN_LENGTH = 6;
    private const int MAX_LENGTH = 32;

    private const string LOWERCASE_PATTERN = '`[a-z]`';
    private const string UPPERCASE_PATTERN = '`[A-Z]`';
    private const string DIGIT_PATTERN = '`[0-9]`';

    public function validate(string $password, string $confirmation): void
    {
        $this->validatePasswordStrength($password);
        $this->validatePasswordConfirmation($password, $confirmation);
    }

    private function validatePasswordStrength(string $password): void
    {
        if (strlen($password) < self::MIN_LENGTH || strlen($password) > self::MAX_LENGTH) {
            throw new InvalidPasswordException(self::MIN_LENGTH, self::MAX_LENGTH);
        }

        if (!preg_match(self::LOWERCASE_PATTERN, $password) ||
            !preg_match(self::UPPERCASE_PATTERN, $password) ||
            !preg_match(self::DIGIT_PATTERN, $password) ||
            !StringUtils::containsSpecialCharacters($password)
        ) {
            throw new InvalidPasswordException(self::MIN_LENGTH, self::MAX_LENGTH);
        }
    }

    private function validatePasswordConfirmation(string $password, string $confirmation): void
    {
        if ($password !== $confirmation) {
            throw new PasswordConfirmationDoesNotMatchException();
        }
    }
}
