<?php

namespace App\Interfaces\Http\Controllers\Web;

use App\Application\Services\LocationService;
use App\Application\Services\ProductService;
use App\Application\Services\SalesService;
use App\Interfaces\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Sales\CreateSalesRequest;

class SaleController extends Controller
{
    public function __construct(
        private SalesService $salesService,
        private LocationService $locationService,
        private ProductService $productService
    ) {}

    public function index()
    {
        return view('sales.index');
    }

    public function create()
    {
        $locations = $this->locationService->findAll();
        $products = $this->productService->findAll();

        return view('sales.create', compact('locations', 'products'));
    }

    public function store(CreateSalesRequest $request)
    {
        $dto = $request->toDto();
        $this->salesService->create($dto);

        return redirect()->route('sales.index')->with('success', 'Sale created successfully.');
    }

    public function show(int $id)
    {
        $sale = $this->salesService->findById($id);

        return view('sales.show', compact('sale'));
    }
}
