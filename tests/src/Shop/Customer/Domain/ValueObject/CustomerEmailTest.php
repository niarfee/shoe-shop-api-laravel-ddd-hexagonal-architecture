<?php

declare(strict_types=1);

namespace Tests\Shop\Customer\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Group;
use Src\Shared\Domain\Exception\InvalidEmailDomainException;
use Src\Shared\Domain\Exception\InvalidEmailFormatException;
use Src\Shop\Customer\Domain\ValueObject\CustomerEmail;
use Tests\Utils\UnitPhpUnitTestCase;

final class CustomerEmailTest extends UnitPhpUnitTestCase
{
    private const VALID_EMAIL = 'test@example.com';
    private const INVALID_EMAIL = 'invalid-email';
    private const INVALID_DOMAIN_EMAIL = 'test@nonexistentdomain12345.com';
    private const EMAIL_WITH_SPACES = '  test@example.com  ';

    #[Group('unit')]
    public function test_create_valid_email(): void
    {
        // GIVEN
        $email = self::VALID_EMAIL;

        // WHEN
        $customerEmail = new CustomerEmail($email);

        // THEN
        $this->assertSame($email, $customerEmail->value());
    }

    #[Group('unit')]
    public function test_create_email_with_spaces_gets_trimmed(): void
    {
        // GIVEN
        $email = self::EMAIL_WITH_SPACES;
        $expected = trim($email);

        // WHEN
        $customerEmail = new CustomerEmail($email);

        // THEN
        $this->assertSame($expected, $customerEmail->value());
    }

    #[Group('unit')]
    public function test_create_invalid_email_format_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidEmailFormatException::class);
        $this->expectExceptionMessage(
            'The email format <' . self::INVALID_EMAIL . '> is not valid.',
        );

        // WHEN
        new CustomerEmail(self::INVALID_EMAIL);
    }

    #[Group('unit')]
    public function test_create_email_with_invalid_domain_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidEmailDomainException::class);
        $this->expectExceptionMessage(
            'The email domain <nonexistentdomain12345.com> does not exist or is not properly configured.',
        );

        // WHEN
        new CustomerEmail(self::INVALID_DOMAIN_EMAIL);
    }

    #[Group('unit')]
    public function test_equals_instances(): void
    {
        // GIVEN
        $email1 = new CustomerEmail(self::VALID_EMAIL);
        $email2 = new CustomerEmail(self::VALID_EMAIL);
        $differentEmail = new CustomerEmail('another@example.com');

        // THEN
        $this->assertTrue($email1->equals($email2));
        $this->assertFalse($email1->equals($differentEmail));
    }
}
