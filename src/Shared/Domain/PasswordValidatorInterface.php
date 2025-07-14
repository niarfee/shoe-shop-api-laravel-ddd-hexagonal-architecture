<?php

declare(strict_types=1);

namespace Src\Shared\Domain;

interface PasswordValidatorInterface
{
    public function validate(string $password, string $confirmation): void;
}
