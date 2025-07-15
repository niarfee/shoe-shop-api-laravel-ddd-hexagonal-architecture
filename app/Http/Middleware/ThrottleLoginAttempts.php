<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Src\Shared\Infrastructure\Http\ApiResponse;
use Src\Shared\Infrastructure\Http\HttpStatusEnum;
use Symfony\Component\HttpFoundation\Response;

class ThrottleLoginAttempts
{
    protected RateLimiter $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * @throws \Src\Shared\Domain\Exception\TooManyLoginAttemptsException When the maximum number of login attempts is exceeded
     */
    public function handle(Request $request, Closure $next, int $maxAttempts = 5, int $decayMinutes = 1): Response
    {
        $key = 'login_attempts_' . $request->ip();

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = $this->limiter->availableIn($key);

            return ApiResponse::fromThrottle(
                retryAfter: $retryAfter,
                maxAttempts: $maxAttempts,
                resource: 'login',
                customMessage: sprintf('Too many login attempts. Please try again in <%d> seconds.', $retryAfter),
            );
        }

        $response = $next($request);

        if ($this->isLoginFailed($response)) {

            $this->limiter->hit($key, $decayMinutes * 60);

            $this->addRateLimitHeaders($response, $key, $maxAttempts);

        } elseif ($this->isLoginSuccess($response)) {
            $this->limiter->clear($key);
        }

        return $response;
    }

    private function isLoginFailed(Response $response): bool
    {
        return $response->getStatusCode() === HttpStatusEnum::Unauthorized->value;
    }

    private function isLoginSuccess(Response $response): bool
    {
        return $response->getStatusCode() === HttpStatusEnum::Ok->value;
    }

    private function addRateLimitHeaders(Response $response, string $key, int $maxAttempts): void
    {
        $remainingAttempts = $maxAttempts - $this->limiter->attempts($key);

        $response->headers->add([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => max(0, $remainingAttempts),
            'X-RateLimit-Reset' => now()->addSeconds($this->limiter->availableIn($key))->getTimestamp(),
        ]);
    }
}
