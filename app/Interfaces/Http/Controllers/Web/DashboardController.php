<?php

namespace App\Interfaces\Http\Controllers\Web;

use App\Application\Services\ProductService;
use App\Application\Services\PurchaseService;
use App\Application\Services\SalesService;
use App\Interfaces\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private SalesService $salesService,
        private PurchaseService $purchaseService
    ) {}

    public function index()
    {
        $totalProducts = $this->productService->getTotalProductCount();
        $salesTodayCount = $this->salesService->getTodaySalesCount();
        $salesTodayTotal = $this->salesService->getTodaySalesTotal();
        $purchasesTodayCount = $this->purchaseService->getTodayPurchasesCount();
        $purchasesTodayTotal = $this->purchaseService->getTodayPurchasesTotal();
        $recentSales = $this->salesService->getRecentSales(5);
        $recentPurchases = $this->purchaseService->getRecentPurchases(5);

        return view('dashboard', compact(
            'totalProducts',
            'salesTodayCount',
            'salesTodayTotal',
            'purchasesTodayCount',
            'purchasesTodayTotal',
            'recentSales',
            'recentPurchases'
        ));
    }
}
