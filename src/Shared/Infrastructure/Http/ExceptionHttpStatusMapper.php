<?php

declare(strict_types=1);

namespace Src\Shared\Infrastructure\Http;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Src\Shared\Domain\Exception\EmptyPasswordException;
use Src\Shared\Domain\Exception\InvalidEmailDomainException;
use Src\Shared\Domain\Exception\InvalidEmailFormatException;
use Src\Shared\Domain\Exception\InvalidPriceException;
use Src\Shared\Domain\Exception\InvalidStringLengthException;
use Src\Shared\Domain\Exception\InvalidTokenStringException;
use Src\Shared\Domain\Exception\InvalidTokenTypeException;
use Src\Shared\Domain\Exception\InvalidUuidException;
use Src\Shared\Domain\Exception\PasswordConfirmationDoesNotMatchException;
use Src\Shop\Customer\Domain\Exception\DuplicateCustomerEmailException;
use Src\Shop\Product\Domain\Exception\ProductNotFoundException;
use Src\Shop\ProductCategory\Domain\Exception\InvalidProductCategorySlugException;
use Src\Shop\ProductCategory\Domain\Exception\NoProductCategoriesExistException;
use Src\Shop\ProductCategory\Domain\Exception\ProductCategoryNotFoundException;
use Src\Shop\Shared\Domain\Exception\ProductVariantStockNegativeException;
use Src\Shop\User\Domain\Exception\InvalidCredentialsException;
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
            $e instanceof PasswordConfirmationDoesNotMatchException => HttpStatusEnum::UnprocessableEntity,
            $e instanceof DuplicateCustomerEmailException => HttpStatusEnum::Conflict,
            $e instanceof InvalidCredentialsException => HttpStatusEnum::Unauthorized,
            $e instanceof EmptyPasswordException => HttpStatusEnum::BadRequest,
            $e instanceof NoProductCategoriesExistException => HttpStatusEnum::NotFound,
            $e instanceof InvalidEmailDomainException => HttpStatusEnum::UnprocessableEntity,
            $e instanceof InvalidTokenTypeException => HttpStatusEnum::Unauthorized,
            $e instanceof InvalidEmailFormatException => HttpStatusEnum::UnprocessableEntity,
            $e instanceof InvalidTokenStringException => HttpStatusEnum::Unauthorized,
            $e instanceof InvalidPriceException => HttpStatusEnum::NotFound,
            $e instanceof ProductNotFoundException => HttpStatusEnum::NotFound,
            $e instanceof ProductCategoryNotFoundException => HttpStatusEnum::NotFound,
            $e instanceof ProductVariantStockNegativeException => HttpStatusEnum::UnprocessableEntity,

            // Laravel exceptions
            $e instanceof AuthenticationException => HttpStatusEnum::Unauthorized,
            $e instanceof ValidationException => HttpStatusEnum::UnprocessableEntity,

            default => HttpStatusEnum::InternalServerError,
        };
    }
}
