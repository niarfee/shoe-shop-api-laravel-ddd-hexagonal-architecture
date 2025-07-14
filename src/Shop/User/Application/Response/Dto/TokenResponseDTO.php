<?php

declare(strict_types=1);

namespace Src\Shop\User\Application\Response\Dto;

class TokenResponseDTO
{
    public function __construct(
        public readonly string $tokenString,
        public readonly string $tokenType,
    ) {
    }
}
