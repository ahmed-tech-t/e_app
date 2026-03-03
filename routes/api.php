<?php

use App\Interfaces\Http\Controllers\CategoryController;
use App\Interfaces\Http\Controllers\LocationController;
use App\Interfaces\Http\Controllers\ProductController;
use App\Interfaces\Http\Controllers\ProductPriceController;
use App\Interfaces\Http\Controllers\SaleUnitController;
use App\Interfaces\Http\Controllers\StockMovementController;
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


        Route::get(
            'products/locations/{id}',
            [ProductController::class, 'findAllByLocation']
        )->name('products.locations.index');

        Route::get(
            'products/{product_id}/locations/{location_id}',
            [ProductController::class, 'findByLocation']
        )->name('products.locations.show');

        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('sale-units', SaleUnitController::class);
        Route::resource('locations', LocationController::class);


        Route::post(
            'stock/transfer',
            [StockMovementController::class, 'transfer']
        )->name('stock.transfer');


        Route::post(
            'stock/search',
            [StockMovementController::class, 'search']
        )->name('stock.search');
    });
});
