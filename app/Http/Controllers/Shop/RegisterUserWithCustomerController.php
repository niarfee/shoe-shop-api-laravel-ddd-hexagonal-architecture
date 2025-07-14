<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Shared\Infrastructure\Http\ApiResponse;
use Src\Shared\Infrastructure\Http\HttpStatusEnum;
use Src\Shop\Shared\Application\Registration\RegisterUserWithCustomerUseCase;
use Src\Shop\User\Application\Response\Presenter\LoggedUserResponsePresenter;

final class RegisterUserWithCustomerController extends Controller
{
    public function __construct(
        private RegisterUserWithCustomerUseCase $registerUserWithCustomerUseCase,
        private LoggedUserResponsePresenter $loggedUserResponsePresenter,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $validatedInput = $request->validate([
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
            'password_confirm' => ['required', 'string'],
        ]);

        $loggedUserResponseDTO = $this->registerUserWithCustomerUseCase->__invoke(
            $validatedInput['first_name'],
            $validatedInput['last_name'],
            $validatedInput['email'],
            $validatedInput['password'],
            $validatedInput['password_confirm'],
        );

        return ApiResponse::success(
            data: $this->loggedUserResponsePresenter->toArray($loggedUserResponseDTO),
            message: 'Customer successfully registered.',
            httpStatus: HttpStatusEnum::Created,
        );
    }
}
