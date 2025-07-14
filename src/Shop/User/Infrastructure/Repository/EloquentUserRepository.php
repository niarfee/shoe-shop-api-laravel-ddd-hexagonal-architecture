<?php

declare(strict_types=1);

namespace Src\Shop\User\Infrastructure\Repository;

use Illuminate\Support\Facades\Auth;
use Src\Shop\User\Domain\Exception\DuplicateUserEmailException;
use Src\Shop\User\Domain\Exception\InvalidCredentialsException;
use Src\Shop\User\Domain\Exception\UserNotFoundByEmailException;
use Src\Shop\User\Domain\Token;
use Src\Shop\User\Domain\User;
use Src\Shop\User\Domain\UserRepositoryInterface;
use Src\Shop\User\Domain\ValueObject\TokenString;
use Src\Shop\User\Domain\ValueObject\TokenType;
use Src\Shop\User\Domain\ValueObject\UserEmail;
use Src\Shop\User\Infrastructure\Mapper\UserMapper;
use Src\Shop\User\Infrastructure\Persistence\Eloquent\UserEloquentModel;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private UserMapper $userMapper,
    ) {
    }

    public function save(User $user): void
    {
        $existing = UserEloquentModel::find($user->id()->value());
        $userEloquent = $this->userMapper->fromDomainToEloquent($user, $existing);

        if (!$existing && $this->existsByEmail($user->email())) {
            throw new DuplicateUserEmailException(
                $user->email(),
            );
        }

        $userEloquent->save();
    }

    public function createToken(UserEmail $userEmail): Token
    {
        $userEloquent = $this->findUserEloquentByEmailOrFail($userEmail);

        return Token::create(
            new TokenString($userEloquent->createToken('auth_token')->plainTextToken),
            new TokenType('Bearer'),
        );
    }

    public function login(UserEmail $userEmail, string $password): User
    {
        if (!Auth::attempt(['email' => $userEmail->value(), 'password' => $password])) {
            throw new InvalidCredentialsException();
        }

        $userEloquent = $this->findUserEloquentByEmailOrFail($userEmail);

        return $this->userMapper->fromEloquentToDomain($userEloquent);
    }

    public function logout(): void
    {
        Auth::user()->tokens()->delete();
    }

    public function findByEmail(UserEmail $userEmail): User
    {
        $userEloquent = $this->findUserEloquentByEmailOrFail($userEmail);

        return $this->userMapper->fromEloquentToDomain($userEloquent);
    }

    private function findUserEloquentByEmailOrFail(UserEmail $userEmail): UserEloquentModel
    {
        $userEloquent = UserEloquentModel::where('email', $userEmail->value())->first();
        if (!$userEloquent) {
            throw new UserNotFoundByEmailException($userEmail);
        }
        return $userEloquent;
    }

    private function existsByEmail(UserEmail $email): bool
    {
        return UserEloquentModel::where('email', $email->value())->exists();
    }
}
