<?php

declare(strict_types=1);

namespace Tests\Shared\Infrastructure\Service;

use PHPUnit\Framework\Attributes\Group;
use Src\Shared\Domain\Exception\InvalidPasswordException;
use Src\Shared\Domain\Exception\PasswordConfirmationDoesNotMatchException;
use Src\Shared\Infrastructure\Service\PasswordValidator;
use Tests\Utils\UnitPhpUnitTestCase;

class PasswordValidatorTest extends UnitPhpUnitTestCase
{
    private const VALID_PASSWORD = 'ValidPass123!';
    private const TOO_SHORT = 'Sh0rt';
    private const TOO_LONG = 'ThisIsAVeryLongPasswordThatExceedsTheMaximumAllowedLength123!';
    private const NO_UPPERCASE = 'invalidpass123!';
    private const NO_LOWERCASE = 'INVALIDPASS123!';
    private const NO_NUMBER = 'InvalidPassword!';
    private const NO_SPECIAL = 'InvalidPassword123';

    private PasswordValidator $validator;

    #[Group('unit')]
    public function test_valid_password_passes_validation(): void
    {
        // GIVEN
        $password = self::VALID_PASSWORD;
        $confirmation = self::VALID_PASSWORD;

        // WHEN
        $this->validator->validate($password, $confirmation);

        // THEN
        $this->assertTrue(true, 'No exceptions should be thrown for valid password');
    }

    #[Group('unit')]
    public function test_too_short_password_throws_exception(): void
    {
        // GIVEN
        $password = self::TOO_SHORT;
        $confirmation = self::TOO_SHORT;
        $this->expectException(InvalidPasswordException::class);
        $this->expectExceptionMessage('The password must have between <6> and <32> characters, a lowercase letter, an uppercase letter, a number and a special character.');

        // WHEN
        $this->validator->validate($password, $confirmation);
    }

    #[Group('unit')]
    public function test_too_long_password_throws_exception(): void
    {
        // GIVEN
        $password = self::TOO_LONG;
        $confirmation = self::TOO_LONG;
        $this->expectException(InvalidPasswordException::class);
        $this->expectExceptionMessage('The password must have between <6> and <32> characters, a lowercase letter, an uppercase letter, a number and a special character.');

        // WHEN
        $this->validator->validate($password, $confirmation);
    }

    #[Group('unit')]
    public function test_password_without_uppercase_throws_exception(): void
    {
        // GIVEN
        $password = self::NO_UPPERCASE;
        $confirmation = self::NO_UPPERCASE;
        $this->expectException(InvalidPasswordException::class);
        $this->expectExceptionMessage('The password must have between <6> and <32> characters, a lowercase letter, an uppercase letter, a number and a special character.');

        // WHEN
        $this->validator->validate($password, $confirmation);
    }

    #[Group('unit')]
    public function test_password_without_lowercase_throws_exception(): void
    {
        // GIVEN
        $password = self::NO_LOWERCASE;
        $confirmation = self::NO_LOWERCASE;
        $this->expectException(InvalidPasswordException::class);
        $this->expectExceptionMessage('The password must have between <6> and <32> characters, a lowercase letter, an uppercase letter, a number and a special character.');

        // WHEN
        $this->validator->validate($password, $confirmation);
    }

    #[Group('unit')]
    public function test_password_without_number_throws_exception(): void
    {
        // GIVEN
        $password = self::NO_NUMBER;
        $confirmation = self::NO_NUMBER;
        $this->expectException(InvalidPasswordException::class);
        $this->expectExceptionMessage('The password must have between <6> and <32> characters, a lowercase letter, an uppercase letter, a number and a special character.');

        // WHEN
        $this->validator->validate($password, $confirmation);
    }

    #[Group('unit')]
    public function test_password_without_special_character_throws_exception(): void
    {
        // GIVEN
        $password = self::NO_SPECIAL;
        $confirmation = self::NO_SPECIAL;
        $this->expectException(InvalidPasswordException::class);
        $this->expectExceptionMessage('The password must have between <6> and <32> characters, a lowercase letter, an uppercase letter, a number and a special character.');

        // WHEN
        $this->validator->validate($password, $confirmation);
    }

    #[Group('unit')]
    public function test_password_confirmation_mismatch_throws_exception(): void
    {
        // GIVEN
        $password = self::VALID_PASSWORD;
        $confirmation = 'DifferentPassword123!';
        $this->expectException(PasswordConfirmationDoesNotMatchException::class);
        $this->expectExceptionMessage('Password confirmation does not match');

        // WHEN
        $this->validator->validate($password, $confirmation);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new PasswordValidator();
    }
}
