<?php

declare(strict_types=1);

namespace Src\Shop\User\Domain;

use Src\Shop\Shared\Domain\ValueObject\CustomerId;
use Src\Shop\User\Domain\ValueObject\UserEmail;
use Src\Shop\User\Domain\ValueObject\UserId;
use Src\Shop\User\Domain\ValueObject\UserPassword;

final class User
{
    private function __construct(
        private readonly UserId $id,
        private readonly CustomerId $customerId,
        private readonly UserEmail $email,
        private readonly UserPassword $password,
    ) {
    }

    public static function create(UserId $id, CustomerId $customerId, UserEmail $email, UserPassword $password): self
    {
        $user = new self($id, $customerId, $email, $password);
        return $user;
    }

    // Public accessors

    public function id(): UserId
    {
        return $this->id;
    }

    public function customerId(): CustomerId
    {
        return $this->customerId;
    }

    public function email(): UserEmail
    {
        return $this->email;
    }

    public function password(): UserPassword
    {
        return $this->password;
    }
}
