<?php

declare(strict_types=1);

namespace Src\Shop\User\Application\Response\Mapper;

use Src\Shop\User\Application\Response\Dto\LoggedUserResponseDTO;
use Src\Shop\User\Application\Response\Dto\TokenResponseDTO;
use Src\Shop\User\Application\Response\Dto\UserResponseDTO;

final class LoggedUserResponseMapper
{
    public function map(UserResponseDTO $userResponseDTO, TokenResponseDTO $tokenResponseDTO): LoggedUserResponseDTO
    {
        return new LoggedUserResponseDTO(
            userResponseDTO: $userResponseDTO,
            tokenResponseDTO: $tokenResponseDTO,
        );
    }
}
