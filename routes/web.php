<?php

use App\Interfaces\Http\Controllers\Web\AuthController;
use App\Interfaces\Http\Controllers\Web\CategoryController;
use App\Interfaces\Http\Controllers\Web\DashboardController;
use App\Interfaces\Http\Controllers\Web\LocationController;
use App\Interfaces\Http\Controllers\Web\ProductController;
use App\Interfaces\Http\Controllers\Web\PurchaseController;
use App\Interfaces\Http\Controllers\Web\PurchaseReturnController;
use App\Interfaces\Http\Controllers\Web\SaleController;
use App\Interfaces\Http\Controllers\Web\SalesReturnController;
use App\Interfaces\Http\Controllers\Web\SaleUnitController;
use App\Interfaces\Http\Controllers\Web\StockController;
use App\Interfaces\Http\Controllers\Web\SupplierController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/healthz', function () {
    return response()->json(['status' => 'healthy'], 200);
})->name('healthz');

Route::post('/locale/{locale}', function ($locale) {
    session(['locale' => in_array($locale, ['en', 'ar']) ? $locale : 'en']);
    return back();
})->name('locale.set');

// Guest-only auth routes
Route::middleware('guest:web')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

// Guarded web endpoints
Route::middleware('auth:web')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{id}', [SupplierController::class, 'show'])->name('suppliers.show');
    Route::get('/suppliers/{id}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::put('/suppliers/{id}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/export',  [ProductController::class, 'exportPage'])     ->name('products.export');
    Route::post('/products/export', [ProductController::class, 'exportDownload']) ->name('products.export.download');
    Route::get('/products/export-template', [ProductController::class, 'exportTemplate'])->name('products.export-template');
    Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');
    Route::get('/products/catalog', [ProductController::class, 'catalogPage'])->name('products.catalog');
    Route::post('/products/catalog', [ProductController::class, 'catalogDownload'])->name('products.catalog.download');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/sale-units', [SaleUnitController::class, 'index'])->name('sale-units.index');
    Route::get('/sale-units/create', [SaleUnitController::class, 'create'])->name('sale-units.create');
    Route::post('/sale-units', [SaleUnitController::class, 'store'])->name('sale-units.store');
    Route::get('/sale-units/{id}/edit', [SaleUnitController::class, 'edit'])->name('sale-units.edit');
    Route::put('/sale-units/{id}', [SaleUnitController::class, 'update'])->name('sale-units.update');
    Route::delete('/sale-units/{id}', [SaleUnitController::class, 'destroy'])->name('sale-units.destroy');

    Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
    Route::get('/locations/create', [LocationController::class, 'create'])->name('locations.create');
    Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');
    Route::get('/locations/{id}/edit', [LocationController::class, 'edit'])->name('locations.edit');
    Route::put('/locations/{id}', [LocationController::class, 'update'])->name('locations.update');
    Route::delete('/locations/{id}', [LocationController::class, 'destroy'])->name('locations.destroy');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
    Route::get('/purchases/{id}', [PurchaseController::class, 'show'])->name('purchases.show');

    Route::get('/purchase-returns', [PurchaseReturnController::class, 'index'])->name('purchase-returns.index');
    Route::get('/purchases/{id}/return', [PurchaseReturnController::class, 'create'])->name('purchase-returns.create');
    Route::post('/purchase-returns', [PurchaseReturnController::class, 'store'])->name('purchase-returns.store');
    Route::get('/purchase-returns/{id}', [PurchaseReturnController::class, 'show'])->name('purchase-returns.show');

    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{id}', [SaleController::class, 'show'])->name('sales.show');

    Route::get('/sales-returns', [SalesReturnController::class, 'index'])->name('sales-returns.index');
    Route::get('/sales/{id}/return', [SalesReturnController::class, 'create'])->name('sales-returns.create');
    Route::post('/sales-returns', [SalesReturnController::class, 'store'])->name('sales-returns.store');
    Route::get('/sales-returns/{id}', [SalesReturnController::class, 'show'])->name('sales-returns.show');

    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::get('/stock/transfer', [StockController::class, 'transfer'])->name('stock.transfer');
    Route::post('/stock/transfer', [StockController::class, 'storeTransfer'])->name('stock.storeTransfer');
});
