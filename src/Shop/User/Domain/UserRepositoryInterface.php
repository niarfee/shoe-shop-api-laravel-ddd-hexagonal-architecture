<?php

declare(strict_types=1);

namespace Src\Shop\User\Domain;

use Src\Shop\User\Domain\ValueObject\UserEmail;

interface UserRepositoryInterface
{
    /**
     * Persists a user. Throws an exception if the email is already taken.
     *
     * @throws DuplicateUserEmailException
     */
    public function save(User $user): void;

    /**
     * Creates an authentication token for the user with the given email.
     *
     * @throws UserNotFoundByEmailException
     */
    public function createToken(UserEmail $userEmail): Token;

    /**
     * Attempts to authenticate the user and returns the authenticated user.
     *
     * @throws InvalidCredentialsException
     * @return User The authenticated user
     */
    public function login(UserEmail $userEmail, string $userPassword): User;

    /**
     * Deletes all tokens for the currently authenticated user.
     */
    public function logout(): void;

    /**
     * Retrieves a user by email.
     *
     * @throws UserNotFoundByEmailException
     */
    public function findByEmail(UserEmail $email): User;
}
