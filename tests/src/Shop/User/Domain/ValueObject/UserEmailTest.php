<?php

declare(strict_types=1);

namespace Tests\Shop\User\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Group;
use Src\Shared\Domain\Exception\InvalidEmailDomainException;
use Src\Shared\Domain\Exception\InvalidEmailFormatException;
use Src\Shop\User\Domain\ValueObject\UserEmail;
use Tests\Utils\UnitPhpUnitTestCase;

final class UserEmailTest extends UnitPhpUnitTestCase
{
    private const VALID_EMAIL = 'test@example.com';
    private const INVALID_EMAIL = 'invalid-email';
    private const INVALID_DOMAIN_EMAIL = 'test@nonexistentdomain12345.com';

    #[Group('unit')]
    public function test_create_valid_email(): void
    {
        // GIVEN
        $email = self::VALID_EMAIL;

        // WHEN
        $userEmail = new UserEmail($email);

        // THEN
        $this->assertSame($email, $userEmail->value());
    }

    #[Group('unit')]
    public function test_invalid_email_format_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidEmailFormatException::class);
        $this->expectExceptionMessage('The email format <' . self::INVALID_EMAIL . '> is not valid.');

        // WHEN
        new UserEmail(self::INVALID_EMAIL);
    }

    #[Group('unit')]
    public function test_invalid_email_domain_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidEmailDomainException::class);
        $this->expectExceptionMessage('The email domain <nonexistentdomain12345.com> does not exist or is not properly configured.');

        // WHEN
        new UserEmail(self::INVALID_DOMAIN_EMAIL);
    }

    #[Group('unit')]
    public function test_empty_email_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidEmailFormatException::class);
        $this->expectExceptionMessage('The email format <> is not valid.');

        // WHEN
        new UserEmail(' ');
    }

    #[Group('unit')]
    public function test_value_method_returns_correct_value(): void
    {
        // GIVEN
        $expectedValue = self::VALID_EMAIL;
        $userEmail = new UserEmail($expectedValue);

        // WHEN
        $value = $userEmail->value();

        // THEN
        $this->assertSame($expectedValue, $value);
    }

    #[Group('unit')]
    public function test_equals_instances(): void
    {
        // GIVEN
        $email1 = new UserEmail(self::VALID_EMAIL);
        $email2 = new UserEmail(self::VALID_EMAIL);
        $differentEmail = new UserEmail('another@example.com');

        // THEN
        $this->assertTrue($email1->equals($email2));
        $this->assertFalse($email1->equals($differentEmail));
    }
}
