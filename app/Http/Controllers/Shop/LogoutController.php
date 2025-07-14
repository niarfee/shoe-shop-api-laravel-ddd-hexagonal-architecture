<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\Shared\Infrastructure\Http\ApiResponse;
use Src\Shared\Infrastructure\Http\HttpStatusEnum;
use Src\Shop\User\Application\LogoutUseCase;

final class LogoutController extends Controller
{
    public function __construct(
        private LogoutUseCase $logoutUseCase,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $this->logoutUseCase->__invoke();

        return ApiResponse::success(
            data: [],
            message: 'You have successfully logged out and the token was successfully deleted',
            httpStatus: HttpStatusEnum::Ok,
        );
    }
}
