<?php

namespace App\Interfaces\Http\Controllers\Web;

use App\Application\Services\ProductService;
use App\Application\Services\PurchaseReturnService;
use App\Application\Services\PurchaseService;
use App\Application\Services\SalesReturnsService;
use App\Application\Services\SalesService;
use App\Interfaces\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private SalesService $salesService,
        private PurchaseService $purchaseService,
        private SalesReturnsService $salesReturnsService,
        private PurchaseReturnService $purchaseReturnService
    ) {}

    public function index()
    {
        $totalProducts = $this->productService->getTotalProductCount();
        $salesTodayCount = $this->salesService->getTodaySalesCount();
        $salesTodayTotal = $this->salesService->getTodaySalesTotal();
        $purchasesTodayCount = $this->purchaseService->getTodayPurchasesCount();
        $purchasesTodayTotal = $this->purchaseService->getTodayPurchasesTotal();
        $salesReturnsTodayCount = $this->salesReturnsService->getTodayReturnsCount();
        $salesReturnsTodayTotal = $this->salesReturnsService->getTodayReturnsTotal();
        $purchaseReturnsTodayCount = $this->purchaseReturnService->getTodayReturnsCount();
        $purchaseReturnsTodayTotal = $this->purchaseReturnService->getTodayReturnsTotal();
        $recentSales = $this->salesService->getRecentSales(5);
        $recentPurchases = $this->purchaseService->getRecentPurchases(5);
        $recentSalesReturns = $this->salesReturnsService->getRecentReturns(5);
        $recentPurchaseReturns = $this->purchaseReturnService->getRecentReturns(5);

        return view('dashboard', compact(
            'totalProducts',
            'salesTodayCount',
            'salesTodayTotal',
            'purchasesTodayCount',
            'purchasesTodayTotal',
            'salesReturnsTodayCount',
            'salesReturnsTodayTotal',
            'purchaseReturnsTodayCount',
            'purchaseReturnsTodayTotal',
            'recentSales',
            'recentPurchases',
            'recentSalesReturns',
            'recentPurchaseReturns'
        ));
    }
}
