<?php

use App\Interfaces\Http\Controllers\CategoryController;
use App\Interfaces\Http\Controllers\LocationController;
use App\Interfaces\Http\Controllers\ProductController;
use App\Interfaces\Http\Controllers\ProductPriceController;
use App\Interfaces\Http\Controllers\PurchaseController;
use App\Interfaces\Http\Controllers\SalesController;
use App\Interfaces\Http\Controllers\SaleUnitController;
use App\Interfaces\Http\Controllers\StockMovementController;
use App\Interfaces\Http\Controllers\SupplierController;
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

        Route::get(
            'locations/products/{code}',
            [
                LocationController::class,
                'productLocations'
            ]
        )->name('locations.products.show');
        Route::resource('locations', LocationController::class);


        Route::resource('suppliers', SupplierController::class);


        Route::post(
            'stock/transfer',
            [StockMovementController::class, 'transfer']
        )->name('stock.transfer');


        Route::get(
            'stock/search',
            [StockMovementController::class, 'search']
        )->name('stock.search');

        Route::get(
            'sales/preview',
            [SalesController::class, 'preSale']
        )->name('sales.preSale');
        Route::post(
            'sales',
            [SalesController::class, 'store']
        )->name('sales.store');

        Route::get(
            'purchases/preview',
            [PurchaseController::class, 'prePurchase']
        )->name('purchases.prePurchase');
        Route::post(
            'purchases',
            [PurchaseController::class, 'store']
        )->name('purchases.store');
    });


});
