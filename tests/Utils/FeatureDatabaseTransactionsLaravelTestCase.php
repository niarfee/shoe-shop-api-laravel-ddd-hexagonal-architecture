<?php

declare(strict_types=1);

namespace Tests\Utils;

use Illuminate\Testing\TestResponse;

abstract class FeatureDatabaseTransactionsLaravelTestCase extends DatabaseTransactionsLaravelTestCase
{
    public function assertValidOrderWithoutLinesJsonStructure(TestResponse $response): void
    {
        $response->assertJsonStructure([
            'status',
            'message',
            'http' => [
                'code',
                'label',
            ],
            'data' => [
                'id',
                'customer_id',
                'status',
                'total_price',
                'total_price_with_symbol',
                'lines' => [],
            ],
        ]);
    }

    public function assertValidOrderWithOneLineJsonStructure(TestResponse $response): void
    {
        $response->assertJsonStructure([
            'status',
            'message',
            'http' => [
                'code',
                'label',
            ],
            'data' => [
                'id',
                'customer_id',
                'status',
                'total_price',
                'total_price_with_symbol',
                'lines' => [
                    [
                        'id',
                        'product_variant_id',
                        'units',
                        'unit_price',
                        'unit_price_with_symbol',
                        'total_price',
                        'total_price_with_symbol',
                    ],
                ],
            ],
        ]);
    }

    public function assertValidOrderWithTwoLineJsonStructure(TestResponse $response): void
    {
        $response->assertJsonStructure([
            'status',
            'message',
            'http' => [
                'code',
                'label',
            ],
            'data' => [
                'id',
                'customer_id',
                'status' => [
                    'value',
                    'label',
                ],
                'total_price',
                'total_price_with_symbol',
                'lines' => [
                    [
                        'id',
                        'product_variant_id',
                        'units',
                        'unit_price',
                        'unit_price_with_symbol',
                        'total_price',
                        'total_price_with_symbol',
                    ],
                    [
                        'id',
                        'product_variant_id',
                        'units',
                        'unit_price',
                        'unit_price_with_symbol',
                        'total_price',
                        'total_price_with_symbol',
                    ],
                ],
            ],
        ]);
    }

    public function assertValidOrdersListJsonStructure(TestResponse $response): void
    {
        $response->assertJsonStructure([
            'status',
            'message',
            'http' => [
                'code',
                'label',
            ],
            'data' => [
                '*' => [
                    'id',
                    'customer_id',
                    'status',
                    'total_price',
                    'total_price_with_symbol',
                    'lines' => [
                        '*' => [
                            'id',
                            'product_variant_id',
                            'units',
                            'unit_price',
                            'unit_price_with_symbol',
                            'total_price',
                            'total_price_with_symbol',
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function assertValidLoggedUserJsonStructure(TestResponse $response): void
    {
        $response->assertJsonStructure([
            'status',
            'message',
            'http' => [
                'code',
                'label',
            ],
            'data' => [
                'user' => [
                    'email',
                ],
                'access_token',
                'token_type',
            ],
        ]);
    }

    public function assertNotAuthenticatedStatusAndAssertJson(TestResponse $response): void
    {
        $response->assertUnauthorized();
        $response->assertJson([
            'status' => 'error',
            'message' => 'Unauthenticated.',
        ]);
    }
}
