<?php

declare(strict_types=1);

namespace Tests\Shop\User\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Group;
use Src\Shared\Domain\Exception\InvalidTokenStringException;
use Src\Shop\User\Domain\ValueObject\TokenString;
use Tests\Utils\UnitPhpUnitTestCase;

final class TokenStringTest extends UnitPhpUnitTestCase
{
    private const VALID_TOKEN = 'valid_token_1234567890abcdefghijklmnopqrstuvwxyz';

    #[Group('unit')]
    public function test_create_valid_token(): void
    {
        // GIVEN
        $tokenValue = self::VALID_TOKEN;

        // WHEN
        $token = new TokenString($tokenValue);

        // THEN
        $this->assertSame($tokenValue, $token->value());
    }

    #[Group('unit')]
    public function test_empty_token_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidTokenStringException::class);
        $this->expectExceptionMessage('Invalid token <>.');

        // WHEN
        new TokenString('');
    }

    #[Group('unit')]
    public function test_whitespace_only_token_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidTokenStringException::class);
        $this->expectExceptionMessage('Invalid token <   >.');

        // WHEN
        new TokenString('   ');
    }

    #[Group('unit')]
    public function test_value_method_returns_correct_value(): void
    {
        // GIVEN
        $expectedValue = self::VALID_TOKEN;
        $token = new TokenString($expectedValue);

        // WHEN
        $value = $token->value();

        // THEN
        $this->assertSame($expectedValue, $value);
    }

    #[Group('unit')]
    public function test_equals(): void
    {
        // GIVEN
        $token1 = new TokenString(self::VALID_TOKEN);
        $token2 = new TokenString(self::VALID_TOKEN);
        $token3 = new TokenString('different_token_1234567890abcdefghijklmnopqrstuvwxyz');

        // THEN
        $this->assertTrue($token1->equals($token2));
        $this->assertFalse($token1->equals($token3));
    }
}
