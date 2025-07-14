<?php

declare(strict_types=1);

namespace Src\Shop\User\Application;

use Src\Shop\User\Domain\UserRepositoryInterface;

final class LogoutUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function __invoke(): void
    {
        $this->userRepository->logout();
    }
}
