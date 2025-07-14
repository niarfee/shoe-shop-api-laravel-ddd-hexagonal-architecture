<?php

declare(strict_types=1);

namespace Src\Shop\User\Application\Response\Mapper;

use Src\Shop\User\Application\Response\Dto\TokenResponseDTO;
use Src\Shop\User\Domain\Token;

final class TokenResponseMapper
{
    public function map(Token $token): TokenResponseDTO
    {
        return new TokenResponseDTO(
            tokenString: $token->tokenString()->value(),
            tokenType: $token->tokenType()->value(),
        );
    }
}
