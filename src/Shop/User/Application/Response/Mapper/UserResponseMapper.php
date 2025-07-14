<?php

declare(strict_types=1);

namespace Src\Shop\User\Application\Response\Mapper;

use Src\Shop\User\Application\Response\Dto\UserResponseDTO;
use Src\Shop\User\Domain\User;

final class UserResponseMapper
{
    public function map(User $user): UserResponseDTO
    {
        return new UserResponseDTO(
            id: $user->id()->value(),
            customerId: $user->customerId()->value(),
            email: $user->email()->value(),
        );
    }
}
