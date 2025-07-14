<?php

declare(strict_types=1);

namespace Src\Shop\Shared\Application\Registration;

use Src\Shared\Domain\TransactionalInterface;
use Src\Shop\Customer\Application\RegisterCustomerUseCase;
use Src\Shop\User\Application\CreateTokenUseCase;
use Src\Shop\User\Application\RegisterUserUseCase;
use Src\Shop\User\Application\Response\Dto\LoggedUserResponseDTO;
use Src\Shop\User\Application\Response\Mapper\LoggedUserResponseMapper;

final class RegisterUserWithCustomerUseCase
{
    public function __construct(
        private RegisterCustomerUseCase $registerCustomerUseCase,
        private RegisterUserUseCase $registerUserUseCase,
        private CreateTokenUseCase $createTokenUseCase,
        private LoggedUserResponseMapper $loggedUserResponseMapper,
        private TransactionalInterface $transactional,
    ) {
    }

    /**
     * @throws DuplicateCustomerEmailException If email is already in use
     * @throws InvalidCredentialsException If credentials are not valid
     */
    public function __invoke(
        string $firstName,
        string $lastName,
        string $email,
        string $password,
        string $passwordConfirm,
    ): LoggedUserResponseDTO {
        $userResponseDTO = null;
        $this->transactional->run(
            function () use (
                $firstName,
                $lastName,
                $email,
                $password,
                $passwordConfirm,
                &$userResponseDTO,
            ) {
                $customerResponseDTO = $this->registerCustomerUseCase->__invoke($firstName, $lastName, $email);
                $userResponseDTO = $this->registerUserUseCase->__invoke(
                    $customerResponseDTO->id,
                    $email,
                    $password,
                    $passwordConfirm,
                );
            },
        );

        $tokenResponseDTO = $this->createTokenUseCase->__invoke($email);

        return $this->loggedUserResponseMapper->map(
            userResponseDTO: $userResponseDTO,
            tokenResponseDTO: $tokenResponseDTO,
        );
    }
}
