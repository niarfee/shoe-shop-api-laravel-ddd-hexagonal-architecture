<?php

declare(strict_types=1);

namespace Src\Shop\User\Domain;

use Src\Shop\User\Domain\ValueObject\TokenString;
use Src\Shop\User\Domain\ValueObject\TokenType;

final class Token
{
    protected readonly string $value;

    public function __construct(
        private readonly TokenString $tokenString,
        private readonly TokenType $tokenType,
    ) {
    }

    public static function create(TokenString $tokenString, TokenType $tokenType): self
    {
        $token = new self($tokenString, $tokenType);

        return $token;
    }

    public function tokenString(): TokenString
    {
        return $this->tokenString;
    }

    public function tokenType(): TokenType
    {
        return $this->tokenType;
    }
}
