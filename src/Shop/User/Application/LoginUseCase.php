<?php

declare(strict_types=1);

namespace Src\Shop\User\Application;

use Src\Shop\User\Application\Response\Dto\LoggedUserResponseDTO;
use Src\Shop\User\Application\Response\Mapper\LoggedUserResponseMapper;
use Src\Shop\User\Application\Response\Mapper\UserResponseMapper;
use Src\Shop\User\Domain\UserRepositoryInterface;
use Src\Shop\User\Domain\ValueObject\UserEmail;

final class LoginUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private CreateTokenUseCase $createTokenUseCase,
        private LoggedUserResponseMapper $loggedUserResponseMapper,
        private UserResponseMapper $userResponseMapper,
    ) {
    }

    /**
     * @throws InvalidCredentialsException If credentials are incorrect
     * @throws UserNotFoundByEmailException If user does not exist
     */
    public function __invoke(
        string $email,
        string $password,
    ): LoggedUserResponseDTO {
        $userEmail = new UserEmail($email);
        $user = $this->userRepository->login($userEmail, $password);
        $tokenResponseDTO = $this->createTokenUseCase->__invoke($email);

        return $this->loggedUserResponseMapper->map(
            userResponseDTO: $this->userResponseMapper->map($user),
            tokenResponseDTO: $tokenResponseDTO,
        );
    }
}
