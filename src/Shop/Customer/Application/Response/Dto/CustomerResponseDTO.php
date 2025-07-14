<?php

declare(strict_types=1);

namespace Src\Shop\Customer\Application\Response\Dto;

class CustomerResponseDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email,
    ) {
    }
}
