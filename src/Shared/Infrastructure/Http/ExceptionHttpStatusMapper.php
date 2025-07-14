<?php

declare(strict_types=1);

namespace Src\Shared\Infrastructure\Http;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Src\Shared\Domain\Exception\InvalidStringLengthException;
use Src\Shared\Domain\Exception\InvalidUuidException;
use Src\Shop\ProductCategory\Domain\Exception\InvalidProductCategorySlugException;
use Src\Shop\ProductCategory\Domain\Exception\NoProductCategoriesExistException;
use Throwable;

final class ExceptionHttpStatusMapper
{
    public static function map(Throwable $e): HttpStatusEnum
    {
        return match (true) {
            // Domain exceptions
            $e instanceof InvalidProductCategorySlugException => HttpStatusEnum::UnprocessableEntity,
            $e instanceof InvalidStringLengthException => HttpStatusEnum::UnprocessableEntity,
            $e instanceof InvalidUuidException => HttpStatusEnum::NotFound,
            $e instanceof NoProductCategoriesExistException => HttpStatusEnum::NotFound,

            // Laravel exceptions
            $e instanceof AuthenticationException => HttpStatusEnum::Unauthorized,
            $e instanceof ValidationException => HttpStatusEnum::UnprocessableEntity,

            default => HttpStatusEnum::InternalServerError,
        };
    }
}
