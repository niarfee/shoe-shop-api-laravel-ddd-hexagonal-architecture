<?php

declare(strict_types=1);

namespace Src\Shop\User\Application\Response\Dto;

class UserResponseDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $email,
        public readonly string $customerId,
    ) {
    }
}
