<?php

use App\Infrastructure\Persistence\Models\Category;
use App\Interfaces\Http\Controllers\CategoryController;
use App\Interfaces\Http\Controllers\ProductController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);

    });
});
