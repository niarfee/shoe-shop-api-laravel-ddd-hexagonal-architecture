<?php

declare(strict_types=1);

namespace Tests\Shop\User\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Group;
use Src\Shared\Domain\Exception\InvalidTokenTypeException;
use Src\Shop\User\Domain\ValueObject\TokenType;
use Tests\Utils\UnitPhpUnitTestCase;

final class TokenTypeTest extends UnitPhpUnitTestCase
{
    private const VALID_TOKEN_TYPE = 'Bearer';
    private const INVALID_TOKEN_TYPE = 'InvalidType';

    #[Group('unit')]
    public function test_create_valid_token_type(): void
    {
        // GIVEN
        $tokenTypeValue = self::VALID_TOKEN_TYPE;

        // WHEN
        $tokenType = new TokenType($tokenTypeValue);

        // THEN
        $this->assertSame($tokenTypeValue, $tokenType->value());
    }

    #[Group('unit')]
    public function test_invalid_token_type_throws_exception(): void
    {
        // GIVEN
        $invalidType = self::INVALID_TOKEN_TYPE;
        $this->expectException(InvalidTokenTypeException::class);
        $this->expectExceptionMessage('Invalid token type <' . $invalidType . '>.');

        // WHEN
        new TokenType($invalidType);
    }

    #[Group('unit')]
    public function test_empty_token_type_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidTokenTypeException::class);
        $this->expectExceptionMessage('Invalid token type <>.');

        // WHEN
        new TokenType('');
    }

    #[Group('unit')]
    public function test_whitespace_only_token_type_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidTokenTypeException::class);
        $this->expectExceptionMessage('Invalid token type <   >.');

        // WHEN
        new TokenType('   ');
    }

    #[Group('unit')]
    public function test_value_method_returns_correct_value(): void
    {
        // GIVEN
        $expectedValue = self::VALID_TOKEN_TYPE;
        $tokenType = new TokenType($expectedValue);

        // WHEN
        $value = $tokenType->value();

        // THEN
        $this->assertSame($expectedValue, $value);
    }

    #[Group('unit')]
    public function test_equals(): void
    {
        // GIVEN
        $tokenType1 = new TokenType(self::VALID_TOKEN_TYPE);
        $tokenType2 = new TokenType(self::VALID_TOKEN_TYPE);

        // THEN
        $this->assertTrue($tokenType1->equals($tokenType2));
    }
}
