<?php

declare(strict_types=1);

namespace Src\Shop\User\Application\Response\Presenter;

use Src\Shop\User\Application\Response\Dto\LoggedUserResponseDTO;

class LoggedUserResponsePresenter
{
    public function toArray(LoggedUserResponseDTO $dto): array
    {
        return [
            'user' => [
                'email' => $dto->userResponseDTO->email,
            ],
            'access_token' => $dto->tokenResponseDTO->tokenString,
            'token_type' => $dto->tokenResponseDTO->tokenType,
        ];
    }
}
