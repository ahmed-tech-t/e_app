<?php

use App\Infrastructure\Persistence\Models\Category;
use App\Interfaces\Http\Controllers\CategoryController;
use App\Interfaces\Http\Controllers\LocationController;
use App\Interfaces\Http\Controllers\ProductBatchController;
use App\Interfaces\Http\Controllers\ProductController;
use App\Interfaces\Http\Controllers\SaleUnitController;
use App\Interfaces\Http\Controllers\StockMovementController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::prefix('v1')->group(function () {

        Route::get('products/search', [ProductController::class, 'search'])->name('products.search');
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('sale-units', SaleUnitController::class);
        Route::resource('locations', LocationController::class);
        Route::get('product-batches/search', [ProductBatchController::class, 'search'])->name('product-batches.search');
        Route::resource('product-batches', ProductBatchController::class);

        Route::post('stock-movements/transfer', [StockMovementController::class, 'transfer'])->name('stock-movements.transfer');
    });
});
