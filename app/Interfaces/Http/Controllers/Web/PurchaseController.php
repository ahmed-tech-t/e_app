<?php

namespace App\Interfaces\Http\Controllers\Web;

use App\Application\Services\LocationService;
use App\Application\Services\ProductService;
use App\Application\Services\PurchaseService;
use App\Application\Services\SupplierService;
use App\Interfaces\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Purchase\CreatePurchaseRequest;

class PurchaseController extends Controller
{
    public function __construct(
        private PurchaseService $purchaseService,
        private SupplierService $supplierService,
        private LocationService $locationService,
        private ProductService $productService
    ) {}

    public function index()
    {
        return view('purchases.index');
    }

    public function create()
    {
        $suppliers = $this->supplierService->findAll();
        $locations = $this->locationService->findAll();
        $products = $this->productService->findAll();

        return view('purchases.create', compact('suppliers', 'locations', 'products'));
    }

    public function store(CreatePurchaseRequest $request)
    {
        $dto = $request->toDto();
        $this->purchaseService->create($dto);

        return redirect()->route('purchases.index')->with('success', 'Purchase created successfully.');
    }

    public function show(int $id)
    {
        $purchase = $this->purchaseService->findById($id);

        return view('purchases.show', compact('purchase'));
    }
}
