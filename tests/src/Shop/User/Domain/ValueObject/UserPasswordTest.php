<?php

declare(strict_types=1);

namespace Tests\Shop\User\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Group;
use Src\Shared\Domain\Exception\EmptyPasswordException;
use Src\Shop\User\Domain\ValueObject\UserPassword;
use Tests\Utils\UnitPhpUnitTestCase;

final class UserPasswordTest extends UnitPhpUnitTestCase
{
    private const VALID_HASH = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

    #[Group('unit')]
    public function test_create_password(): void
    {
        // GIVEN
        $hash = self::VALID_HASH;

        // WHEN
        $userPassword = new UserPassword($hash);

        // THEN
        $this->assertSame($hash, $userPassword->value());
    }

    #[Group('unit')]
    public function test_empty_password_throws_exception(): void
    {
        // GIVEN
        $this->expectException(EmptyPasswordException::class);
        $this->expectExceptionMessage('The password cannot be empty.');

        // WHEN
        new UserPassword('');
    }

    #[Group('unit')]
    public function test_whitespace_only_password_is_allowed(): void
    {
        // GIVEN
        $hashWithWhitespace = ' ' . self::VALID_HASH . ' ';

        // WHEN
        $userPassword = new UserPassword($hashWithWhitespace);

        // THEN (no exception should be thrown)
        $this->assertSame($hashWithWhitespace, $userPassword->value());
    }

    #[Group('unit')]
    public function test_value_method_returns_correct_value(): void
    {
        // GIVEN
        $expectedValue = self::VALID_HASH;
        $userPassword = new UserPassword($expectedValue);

        // WHEN
        $value = $userPassword->value();

        // THEN
        $this->assertSame($expectedValue, $value);
    }

    #[Group('unit')]
    public function test_equals(): void
    {
        // GIVEN
        $password1 = new UserPassword(self::VALID_HASH);
        $password2 = new UserPassword(self::VALID_HASH);
        $password3 = new UserPassword('$2y$10$differenthash123456789012345678901234567890123456789');

        // THEN
        $this->assertTrue($password1->equals($password2));
        $this->assertFalse($password1->equals($password3));
    }
}
