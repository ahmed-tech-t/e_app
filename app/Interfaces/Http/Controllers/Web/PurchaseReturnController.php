<?php

namespace App\Interfaces\Http\Controllers\Web;

use App\Application\Services\LocationService;
use App\Application\Services\PurchaseReturnService;
use App\Application\Services\PurchaseService;
use App\Interfaces\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\PurchaseReturn\CreatePurchaseReturnRequest;

class PurchaseReturnController extends Controller
{
    public function __construct(
        private PurchaseReturnService $purchaseReturnService,
        private PurchaseService $purchaseService,
        private LocationService $locationService
    ) {}

    public function index()
    {
        return view('purchase-returns.index');
    }

    public function create(int $id)
    {
        $purchase = $this->purchaseService->findById($id);
        $locations = $this->locationService->findAll();

        return view('purchase-returns.create', compact('purchase', 'locations'));
    }

    public function store(CreatePurchaseReturnRequest $request)
    {
        $dto = $request->toDto();
        $this->purchaseReturnService->create($dto);

        return redirect()->route('purchase-returns.index')->with('success', 'Purchase return created successfully.');
    }

    public function show(int $id)
    {
        $return = $this->purchaseReturnService->findById($id);
        $originalPurchase = $this->purchaseService->findById($return->purchase_id);

        return view('purchase-returns.show', compact('return', 'originalPurchase'));
    }
}
