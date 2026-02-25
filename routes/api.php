<?php

use App\Interfaces\Http\Controllers\CategoryController;
use App\Interfaces\Http\Controllers\LocationController;
use App\Interfaces\Http\Controllers\ProductController;
use App\Interfaces\Http\Controllers\ProductPriceController;
use App\Interfaces\Http\Controllers\SaleUnitController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::prefix('v1')->group(function () {

        Route::post(
            'products/{id}/price',
            [ProductPriceController::class, 'setNewPrice']
        )->name('products.setNewPrice');



        Route::get(
            'products/{id}/price',
            [ProductPriceController::class, 'getProductPriceHistory']
        )->name('products.prices.history');


        Route::get(
            'products/search',
            [ProductController::class, 'search']
        )->name('products.search');

        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('sale-units', SaleUnitController::class);
        Route::resource('locations', LocationController::class);

    });
});
