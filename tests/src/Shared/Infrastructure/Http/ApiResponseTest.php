<?php

declare(strict_types=1);

namespace Tests\Shared\Infrastructure\Http;

use Illuminate\Support\Facades\Config;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\Group;
use RuntimeException;
use Src\Shared\Domain\Exception\InvalidEmailFormatException;
use Src\Shared\Infrastructure\Http\ApiResponse;
use Src\Shared\Infrastructure\Http\HttpStatusEnum;
use Src\Shop\ProductCategory\Domain\Exception\NoProductCategoriesExistException;
use Tests\Utils\IntegrationDatabaseTransactionsLaravelTestCase;

class ApiResponseTest extends IntegrationDatabaseTransactionsLaravelTestCase
{
    #[Group('integration')]
    public function test_it_returns_success_response_with_expected_structure(): void
    {
        $data = ['foo' => 'bar'];
        $message = 'Operation completed successfully.';
        $status = HttpStatusEnum::Created;

        $response = TestResponse::fromBaseResponse(
            ApiResponse::success($data, $message, $status),
        );

        $response->assertStatus(201);
        $response->assertExactJson([
            'status' => 'success',
            'message' => $message,
            'http' => [
                'code' => 201,
                'label' => 'Created',
            ],
            'data' => $data,
        ]);
    }

    #[Group('integration')]
    public function test_it_returns_error_response_from_exception_with_trace_in_debug_and_mapped_exception(): void
    {
        Config::set('app.debug', true);
        $exception = new InvalidEmailFormatException('any@email.com');

        $response = TestResponse::fromBaseResponse(
            ApiResponse::fromException($exception),
        );

        $response->assertStatus(HttpStatusEnum::UnprocessableEntity->code());
        $json = $response->getData(assoc: true);
        $this->assertSame('error', $json['status']);
        $this->assertSame('The email format <any@email.com> is not valid.', $json['message']);
        $this->assertSame(422, $json['http']['code']);
        $this->assertSame('Unprocessable Entity', $json['http']['label']);
        $this->assertArrayHasKey('trace', $json['errors']);
        $this->assertIsArray($json['errors']['trace']);
    }

    #[Group('integration')]
    public function test_it_returns_error_response_from_exception_without_trace_when_debug_disabled_and_mapped_exception(): void
    {
        Config::set('app.debug', false);
        $exception = new NoProductCategoriesExistException();

        $response = TestResponse::fromBaseResponse(
            ApiResponse::fromException($exception),
        );

        $response->assertStatus(HttpStatusEnum::NotFound->code());
        $response->assertExactJson([
            'status' => 'error',
            'message' => 'No categories found.',
            'http' => [
                'code' => 404,
                'label' => 'Not Found',
            ],
            'errors' => [],
        ]);
    }

    #[Group('integration')]
    public function test_from_exception_response_with_unmapped_exception(): void
    {
        Config::set('app.debug', false);
        $exception = new RuntimeException('Unexpected failure');

        $response = TestResponse::fromBaseResponse(
            ApiResponse::fromException($exception),
        );

        $response->assertStatus(HttpStatusEnum::InternalServerError->code());
        $response->assertExactJson([
            'status' => 'error',
            'message' => 'Unexpected failure',
            'http' => [
                'code' => 500,
                'label' => 'Internal Server Error',
            ],
            'errors' => [],
        ]);
    }
}
