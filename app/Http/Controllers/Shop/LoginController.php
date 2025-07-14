<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Shared\Infrastructure\Http\ApiResponse;
use Src\Shared\Infrastructure\Http\HttpStatusEnum;
use Src\Shop\User\Application\LoginUseCase;
use Src\Shop\User\Application\Response\Presenter\LoggedUserResponsePresenter;

final class LoginController extends Controller
{
    public function __construct(
        private LoginUseCase $loginUseCase,
        private LoggedUserResponsePresenter $loggedUserResponsePresenter,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $validatedInput = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loggedUserResponseDTO = $this->loginUseCase->__invoke(
            $validatedInput['email'],
            $validatedInput['password'],
        );

        return ApiResponse::success(
            data: $this->loggedUserResponsePresenter->toArray($loggedUserResponseDTO),
            message: "Hi {$loggedUserResponseDTO->userResponseDTO->email}",
            httpStatus: HttpStatusEnum::Ok,
        );
    }
}
