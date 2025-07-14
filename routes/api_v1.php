<?php

declare(strict_types=1);

use App\Http\Controllers\Shop\GetAllProductCategoriesController;

// Categories and Products
Route::get('/categories', GetAllProductCategoriesController::class);
