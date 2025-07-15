<?php

declare(strict_types=1);

namespace Src\Shared\Infrastructure\Http;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Src\Shared\Domain\Exception\EmptyPasswordException;
use Src\Shared\Domain\Exception\InvalidEmailDomainException;
use Src\Shared\Domain\Exception\InvalidEmailFormatException;
use Src\Shared\Domain\Exception\InvalidPasswordException;
use Src\Shared\Domain\Exception\InvalidPriceException;
use Src\Shared\Domain\Exception\InvalidStringLengthException;
use Src\Shared\Domain\Exception\InvalidTokenStringException;
use Src\Shared\Domain\Exception\InvalidTokenTypeException;
use Src\Shared\Domain\Exception\InvalidUuidException;
use Src\Shared\Domain\Exception\PasswordConfirmationDoesNotMatchException;
use Src\Shop\Customer\Domain\Exception\CustomerNotFoundByEmailException;
use Src\Shop\Customer\Domain\Exception\CustomerNotFoundException;
use Src\Shop\Customer\Domain\Exception\DuplicateCustomerEmailException;
use Src\Shop\Order\Domain\Exception\CartEmptyException;
use Src\Shop\Order\Domain\Exception\CartNotFoundException;
use Src\Shop\Order\Domain\Exception\InvalidOrderStatusException;
use Src\Shop\Order\Domain\Exception\OrderAlreadyContainsOrderLineForProductVariantException;
use Src\Shop\Order\Domain\Exception\OrderDoesNotContainOrderLineException;
use Src\Shop\Order\Domain\Exception\OrderDoesNotContainOrderLineForProductVariantException;
use Src\Shop\Order\Domain\Exception\OrderLineDoesNotBelongToOrderException;
use Src\Shop\Order\Domain\Exception\OrderLineNotFoundException;
use Src\Shop\Order\Domain\Exception\OrderLineVariantIdDoesNotMatchException;
use Src\Shop\Order\Domain\Exception\OrderNotInCartException;
use Src\Shop\Product\Domain\Exception\InvalidProductDescriptionException;
use Src\Shop\Product\Domain\Exception\InvalidProductNameException;
use Src\Shop\Product\Domain\Exception\InvalidProductVariantColorException;
use Src\Shop\Product\Domain\Exception\InvalidProductVariantSizeException;
use Src\Shop\Product\Domain\Exception\ProductAlreadyContainsProductVariantForSizeAndColorException;
use Src\Shop\Product\Domain\Exception\ProductNotFoundByVariantIdException;
use Src\Shop\Product\Domain\Exception\ProductNotFoundException;
use Src\Shop\Product\Domain\Exception\ProductVariantColorDoesNotMatchException;
use Src\Shop\Product\Domain\Exception\ProductVariantDoesNotBelongToProductException;
use Src\Shop\Product\Domain\Exception\ProductVariantNotFoundException;
use Src\Shop\Product\Domain\Exception\ProductVariantSizeDoesNotMatchException;
use Src\Shop\ProductCategory\Domain\Exception\InvalidProductCategorySlugException;
use Src\Shop\ProductCategory\Domain\Exception\NoProductCategoriesExistException;
use Src\Shop\ProductCategory\Domain\Exception\ProductCategoryNotFoundException;
use Src\Shop\Shared\Domain\Exception\OrderLineUnitNegativeException;
use Src\Shop\Shared\Domain\Exception\ProductVariantStockNegativeException;
use Src\Shop\Shared\Domain\Exception\ProductVariantStockRequestedNegativeException;
use Src\Shop\User\Domain\Exception\DuplicateUserEmailException;
use Src\Shop\User\Domain\Exception\InvalidCredentialsException;
use Src\Shop\User\Domain\Exception\UserNotFoundByEmailException;
use Throwable;

final class ExceptionHttpStatusMapper
{
    public static function map(Throwable $e): HttpStatusEnum
    {
        return match (true) {
            // Domain exceptions
            $e instanceof CartEmptyException => HttpStatusEnum::NotFound,
            $e instanceof CartNotFoundException => HttpStatusEnum::NotFound,
            $e instanceof CustomerNotFoundByEmailException => HttpStatusEnum::NotFound,
            $e instanceof CustomerNotFoundException => HttpStatusEnum::NotFound,
            $e instanceof DuplicateCustomerEmailException => HttpStatusEnum::Conflict,
            $e instanceof DuplicateUserEmailException => HttpStatusEnum::Conflict,
            $e instanceof EmptyPasswordException => HttpStatusEnum::BadRequest,
            $e instanceof InvalidCredentialsException => HttpStatusEnum::Unauthorized,
            $e instanceof InvalidEmailDomainException => HttpStatusEnum::UnprocessableEntity,
            $e instanceof InvalidEmailFormatException => HttpStatusEnum::UnprocessableEntity,
            $e instanceof InvalidStringLengthException => HttpStatusEnum::UnprocessableEntity,
            $e instanceof InvalidOrderStatusException => HttpStatusEnum::UnprocessableEntity,
            $e instanceof InvalidPasswordException => HttpStatusEnum::BadRequest,
            $e instanceof InvalidPriceException => HttpStatusEnum::NotFound,
            $e instanceof InvalidProductCategorySlugException => HttpStatusEnum::UnprocessableEntity,
            $e instanceof InvalidProductDescriptionException => HttpStatusEnum::UnprocessableEntity,
            $e instanceof InvalidProductNameException => HttpStatusEnum::UnprocessableEntity,
            $e instanceof InvalidProductVariantColorException => HttpStatusEnum::UnprocessableEntity,
            $e instanceof InvalidProductVariantSizeException => HttpStatusEnum::UnprocessableEntity,
            $e instanceof InvalidTokenStringException => HttpStatusEnum::Unauthorized,
            $e instanceof InvalidTokenTypeException => HttpStatusEnum::Unauthorized,
            $e instanceof InvalidUuidException => HttpStatusEnum::NotFound,
            $e instanceof NoProductCategoriesExistException => HttpStatusEnum::NotFound,
            $e instanceof OrderAlreadyContainsOrderLineForProductVariantException => HttpStatusEnum::Conflict,
            $e instanceof OrderDoesNotContainOrderLineException => HttpStatusEnum::NotFound,
            $e instanceof OrderDoesNotContainOrderLineForProductVariantException => HttpStatusEnum::NotFound,
            $e instanceof OrderLineDoesNotBelongToOrderException => HttpStatusEnum::Forbidden,
            $e instanceof OrderLineNotFoundException => HttpStatusEnum::NotFound,
            $e instanceof OrderLineUnitNegativeException => HttpStatusEnum::UnprocessableEntity,
            $e instanceof OrderLineVariantIdDoesNotMatchException => HttpStatusEnum::Conflict,
            $e instanceof OrderNotInCartException => HttpStatusEnum::Conflict,
            $e instanceof PasswordConfirmationDoesNotMatchException => HttpStatusEnum::UnprocessableEntity,
            $e instanceof ProductAlreadyContainsProductVariantForSizeAndColorException => HttpStatusEnum::Conflict,
            $e instanceof ProductCategoryNotFoundException => HttpStatusEnum::NotFound,
            $e instanceof ProductNotFoundByVariantIdException => HttpStatusEnum::NotFound,
            $e instanceof ProductNotFoundException => HttpStatusEnum::NotFound,
            $e instanceof ProductVariantColorDoesNotMatchException => HttpStatusEnum::Conflict,
            $e instanceof ProductVariantDoesNotBelongToProductException => HttpStatusEnum::Forbidden,
            $e instanceof ProductVariantNotFoundException => HttpStatusEnum::NotFound,
            $e instanceof ProductVariantSizeDoesNotMatchException => HttpStatusEnum::Conflict,
            $e instanceof ProductVariantStockNegativeException => HttpStatusEnum::UnprocessableEntity,
            $e instanceof ProductVariantStockRequestedNegativeException => HttpStatusEnum::UnprocessableEntity,
            $e instanceof UserNotFoundByEmailException => HttpStatusEnum::NotFound,

            // Laravel exceptions
            $e instanceof AuthenticationException => HttpStatusEnum::Unauthorized,
            $e instanceof ValidationException => HttpStatusEnum::UnprocessableEntity,

            default => HttpStatusEnum::InternalServerError,
        };
    }
}
