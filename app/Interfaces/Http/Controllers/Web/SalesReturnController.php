<?php

namespace App\Interfaces\Http\Controllers\Web;

use App\Application\Services\LocationService;
use App\Application\Services\SalesReturnsService;
use App\Application\Services\SalesService;
use App\Interfaces\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\SalesReturns\CreateSalesReturnsRequest;

class SalesReturnController extends Controller
{
    public function __construct(
        private SalesReturnsService $salesReturnsService,
        private SalesService $salesService,
        private LocationService $locationService
    ) {}

    public function index()
    {
        return view('sales-returns.index');
    }

    public function create(int $id)
    {
        $sale = $this->salesService->findById($id);
        $locations = $this->locationService->findAll();

        return view('sales-returns.create', compact('sale', 'locations'));
    }

    public function store(CreateSalesReturnsRequest $request)
    {
        $dto = $request->toDto();
        $this->salesReturnsService->create($dto);

        return redirect()->route('sales-returns.index')->with('success', 'Sales return created successfully.');
    }

    public function show(int $id)
    {
        $return = $this->salesReturnsService->findById($id);
        $originalSale = $this->salesService->findById($return->sales_id);

        return view('sales-returns.show', compact('return', 'originalSale'));
    }
}
