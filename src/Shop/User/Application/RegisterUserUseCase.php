<?php

declare(strict_types=1);

namespace Src\Shop\User\Application;

use Illuminate\Support\Facades\Hash;
use Src\Shared\Domain\PasswordValidatorInterface;
use Src\Shop\Shared\Domain\ValueObject\CustomerId;
use Src\Shop\User\Application\Response\Dto\UserResponseDTO;
use Src\Shop\User\Application\Response\Mapper\UserResponseMapper;
use Src\Shop\User\Domain\User;
use Src\Shop\User\Domain\UserRepositoryInterface;
use Src\Shop\User\Domain\ValueObject\UserEmail;
use Src\Shop\User\Domain\ValueObject\UserId;
use Src\Shop\User\Domain\ValueObject\UserPassword;

final class RegisterUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private CustomerValidator $customerValidator,
        private UserResponseMapper $userResponseMapper,
        private PasswordValidatorInterface $passwordValidator,
    ) {
    }

    /**
     * @throws DuplicateUserEmailException If email is already registered
     * @throws CustomerNotFoundException If customer does not exist
     */
    public function __invoke(
        string $customerId,
        string $email,
        string $password,
        string $passwordConfirm,
    ): UserResponseDTO {
        $customerId = new CustomerId($customerId);
        $this->customerValidator->validateCustomerExists($customerId);

        $userEmail = new UserEmail($email);

        $this->passwordValidator->validate($password, $passwordConfirm);

        $userPassword = new UserPassword(Hash::make($password));

        $userToCreate = User::create(
            id: UserId::generate(),
            customerId: $customerId,
            email: $userEmail,
            password: $userPassword,
        );
        $this->userRepository->save($userToCreate);

        return $this->userResponseMapper->map($userToCreate);
    }
}
