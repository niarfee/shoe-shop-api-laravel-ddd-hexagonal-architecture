<?php

declare(strict_types=1);

namespace Src\Shared\Infrastructure\Http;

enum HttpStatusEnum: int
{
    case Ok = 200;
    case Created = 201;
    case BadRequest = 400;
    case Unauthorized = 401;
    case Forbidden = 403;
    case NotFound = 404;
    case Conflict = 409;
    case UnprocessableEntity = 422;
    case TooManyRequests = 429;
    case InternalServerError = 500;

    public function code(): int
    {
        return $this->value;
    }

    public function label(): string
    {
        return match ($this) {
            self::Ok => 'OK',
            self::Created => 'Created',
            self::BadRequest => 'Bad Request',
            self::Unauthorized => 'Unauthorized',
            self::Forbidden => 'Forbidden',
            self::NotFound => 'Not Found',
            self::Conflict => 'Conflict',
            self::UnprocessableEntity => 'Unprocessable Entity',
            self::TooManyRequests => 'Too Many Requests',
            self::InternalServerError => 'Internal Server Error',
        };
    }
}
