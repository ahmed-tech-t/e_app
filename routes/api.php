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

Route::prefix('e_app/api')->group(function () {
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
        Route::get('products', [ProductController::class, 'index']);
        Route::post('products', [ProductController::class, 'store']);
        Route::get('products/{id}', [ProductController::class, 'show']);
        Route::put('products/{id}', [ProductController::class, 'update']);
        Route::delete('products/{id}', [ProductController::class, 'destroy']);

        Route::get('categories', [CategoryController::class, 'index']);
        Route::post('categories', [CategoryController::class, 'store']);
        Route::get('categories/{id}', [CategoryController::class, 'show']);
        Route::put('categories/{id}', [CategoryController::class, 'update']);
        Route::delete('categories/{id}', [CategoryController::class, 'destroy']);


        Route::get('sale-units', [SaleUnitController::class, 'index']);
        Route::post('sale-units', [SaleUnitController::class, 'store']);
        Route::get('sale-units/{id}', [SaleUnitController::class, 'show']);
        Route::put('sale-units/{id}', [SaleUnitController::class, 'update']);
        Route::delete('sale-units/{id}', [SaleUnitController::class, 'destroy']);

        Route::get(
            'locations/products/{code}',
            [
                LocationController::class,
                'productLocations'
            ]
        )->name('locations.products.show');

        Route::get('locations', [LocationController::class, 'index']);
        Route::post('locations', [LocationController::class, 'store']);
        Route::get('locations/{id}', [LocationController::class, 'show']);
        Route::put('locations/{id}', [LocationController::class, 'update']);
        Route::delete('locations/{id}', [LocationController::class, 'destroy']);


        Route::get('suppliers', [SupplierController::class, 'index']);
        Route::post('suppliers', [SupplierController::class, 'store']);
        Route::get('suppliers/{id}', [SupplierController::class, 'show']);
        Route::put('suppliers/{id}', [SupplierController::class, 'update']);
        Route::delete('suppliers/{id}', [SupplierController::class, 'destroy']);



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
