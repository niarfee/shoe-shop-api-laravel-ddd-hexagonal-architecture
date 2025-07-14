<?php

declare(strict_types=1);

namespace Src\Shared\Infrastructure\Http;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Throwable;

final class ExceptionHttpStatusMapper
{
    public static function map(Throwable $e): HttpStatusEnum
    {
        return match (true) {
            // Domain exceptions

            // Laravel exceptions
            $e instanceof AuthenticationException => HttpStatusEnum::Unauthorized,
            $e instanceof ValidationException => HttpStatusEnum::UnprocessableEntity,

            default => HttpStatusEnum::InternalServerError,
        };
    }
}
