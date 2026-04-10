<?php

namespace App\Interfaces\Http\Controllers\Web;

use App\Application\Services\LocationService;
use App\Application\Services\ProductService;
use App\Application\Services\StockService;
use App\Interfaces\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\StockMovement\TransferProductRequest;

class StockController extends Controller
{
    public function __construct(
        private StockService $stockService,
        private ProductService $productService,
        private LocationService $locationService
    ) {}

    public function index()
    {
        return view('stock.index');
    }

    public function transfer()
    {
        $products = $this->productService->findAll();
        $locations = $this->locationService->findAll();

        return view('stock.transfer', compact('products', 'locations'));
    }

    public function storeTransfer(TransferProductRequest $request)
    {
        $dto = $request->toDto();
        $this->stockService->transferProduct($dto);

        return redirect()->route('stock.index')->with('success', 'Stock transferred successfully.');
    }
}
