<?php

declare(strict_types=1);

namespace Src\Shared\Domain\ValueObject;

use Src\Shared\Domain\Exception\InvalidUuidException;
use Symfony\Component\Uid\Uuid;

abstract class UuidValueObject
{
    final public function __construct(protected readonly string $value)
    {
        $this->ensureIsValidUuid($value);
    }

    final public static function generate(): static
    {
        return new static(Uuid::v7()->toRfc4122());
    }

    final public function value(): string
    {
        return $this->value;
    }

    final public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    private function ensureIsValidUuid(string $id): void
    {
        if (!Uuid::isValid($this->value)) {
            throw new InvalidUuidException(static::class, $id);
        }
    }
}
