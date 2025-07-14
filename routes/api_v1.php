<?php

declare(strict_types=1);

use App\Http\Controllers\Shop\GetAllProductCategoriesController;
use App\Http\Controllers\Shop\LoginController;
use App\Http\Controllers\Shop\LogoutController;
use App\Http\Controllers\Shop\RegisterUserWithCustomerController;

// Authentication
Route::post('/auth/register', RegisterUserWithCustomerController::class);
Route::post('/auth/login', LoginController::class)->middleware('throttle.login:5,1');
Route::post('/auth/logout', LogoutController::class)->middleware('auth:sanctum');

// Categories and Products
Route::get('/categories', GetAllProductCategoriesController::class);
