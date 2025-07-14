<?php

declare(strict_types=1);

namespace Src\Shared\Infrastructure\Http;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ApiResponse
{
    public static function success(
        array $data = [],
        string $message = 'Success',
        HttpStatusEnum $httpStatus = HttpStatusEnum::Ok,
    ): JsonResponse {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'http' => [
                'code' => $httpStatus->code(),
                'label' => $httpStatus->label(),
            ],
            'data' => $data,
        ], $httpStatus->code());
    }

    public static function fromThrottle(
        int $retryAfter,
        int $maxAttempts,
        string $resource = 'request',
        ?string $customMessage = null,
    ): JsonResponse {
        $message = $customMessage ?? sprintf(
            'Too many attempts. Please try again in <%d> seconds.',
            $retryAfter,
        );

        return response()->json([
            'status' => 'error',
            'message' => $message,
            'http' => [
                'code' => HttpStatusEnum::TooManyRequests->code(),
                'label' => HttpStatusEnum::TooManyRequests->label(),
            ],
            'errors' => [
                'throttle' => [
                    'resource' => $resource,
                    'retry_after' => $retryAfter,
                    'max_attempts' => $maxAttempts,
                ],
            ],
        ], HttpStatusEnum::TooManyRequests->code())
        ->withHeaders([
            'Retry-After' => $retryAfter,
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => 0,
            'X-RateLimit-Reset' => now()->addSeconds($retryAfter)->getTimestamp(),
        ]);
    }

    public static function fromException(\Throwable $e): JsonResponse
    {
        $httpStatusEnum = ExceptionHttpStatusMapper::map($e);

        $errors = [];

        if ($e instanceof ValidationException) {
            $errors = $e->errors();
        } elseif (config('app.env') != 'production' && config('app.debug')) {
            $errors = ['trace' => self::fullTrace($e)];
        }

        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'http' => [
                'code' => $httpStatusEnum->code(),
                'label' => $httpStatusEnum->label(),
            ],
            'errors' => $errors,
        ], $httpStatusEnum->code());
    }

    private static function fullTrace(\Throwable $e): array
    {
        // Add first trace line to the trace array
        $trace = $e->getTrace();
        array_unshift($trace, [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'function' => '',  // No specific function in the source line
            'class' => get_class($e),
            'type' => '->',
        ]);
        return $trace;
    }
}
