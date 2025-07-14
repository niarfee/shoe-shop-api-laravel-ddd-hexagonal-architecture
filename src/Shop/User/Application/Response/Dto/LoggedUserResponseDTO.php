<?php

declare(strict_types=1);

namespace Src\Shop\User\Application\Response\Dto;

final class LoggedUserResponseDTO
{
    public function __construct(
        public readonly UserResponseDTO $userResponseDTO,
        public readonly TokenResponseDTO $tokenResponseDTO,
    ) {
    }
}
