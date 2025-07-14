<?php

declare(strict_types=1);

namespace Src\Shop\User\Application;

use Src\Shop\User\Application\Response\Dto\TokenResponseDTO;
use Src\Shop\User\Application\Response\Mapper\TokenResponseMapper;
use Src\Shop\User\Domain\UserRepositoryInterface;
use Src\Shop\User\Domain\ValueObject\UserEmail;

final class CreateTokenUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private TokenResponseMapper $tokenResponseMapper,
    ) {
    }

    public function __invoke(string $email): TokenResponseDTO
    {
        $userEmail = new UserEmail($email);

        $token = $this->userRepository->createToken($userEmail);

        return $this->tokenResponseMapper->map($token);
    }
}
