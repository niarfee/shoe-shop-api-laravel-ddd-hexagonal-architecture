<?php

declare(strict_types=1);

use App\Http\Controllers\Shop\ConfirmCartController;
use App\Http\Controllers\Shop\GetAllProductCategoriesController;
use App\Http\Controllers\Shop\GetCartController;
use App\Http\Controllers\Shop\GetOrdersController;
use App\Http\Controllers\Shop\GetProductController;
use App\Http\Controllers\Shop\GetProductsByProductCategoryController;
use App\Http\Controllers\Shop\LoginController;
use App\Http\Controllers\Shop\LogoutController;
use App\Http\Controllers\Shop\RegisterUserWithCustomerController;
use App\Http\Controllers\Shop\UpdateCartController;
use Illuminate\Support\Facades\Route;

// Authentication
Route::post('/auth/register', RegisterUserWithCustomerController::class);
Route::post('/auth/login', LoginController::class)->middleware('throttle.login:5,1');
Route::post('/auth/logout', LogoutController::class)->middleware('auth:sanctum');

// Categories and Products
Route::get('/categories', GetAllProductCategoriesController::class);
Route::get('/categories/{id}/products', GetProductsByProductCategoryController::class);
Route::get('/products/{id}', GetProductController::class);

// Cart
Route::get('/cart', GetCartController::class)->middleware('auth:sanctum');
Route::put('/cart/update', UpdateCartController::class)->middleware('auth:sanctum');
Route::post('/cart/confirm', ConfirmCartController::class)->middleware('auth:sanctum');

// Orders
Route::get('/orders', GetOrdersController::class)->middleware('auth:sanctum');
