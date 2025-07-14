<?php

declare(strict_types=1);

namespace Src\Shared\Domain\ValueObject;

use Src\Shared\Domain\Exception\InvalidEmailDomainException;
use Src\Shared\Domain\Exception\InvalidEmailFormatException;

abstract class EmailValueObject
{
    protected readonly string $value;

    public function __construct(string $value)
    {
        $this->value = trim($value);
        $this->validate($this->value);
    }

    final public function value(): string
    {
        return $this->value;
    }

    private function validate(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailFormatException($email);
        }

        $this->validateDomain($email);
    }

    private function validateDomain(string $email): void
    {
        $domain = substr(strrchr($email, '@'), 1);

        // Check if the domain has MX or A/AAAA records
        if (!checkdnsrr($domain, 'MX') && !checkdnsrr($domain, 'A') && !checkdnsrr($domain, 'AAAA')) {
            throw new InvalidEmailDomainException($domain);
        }
    }
}
