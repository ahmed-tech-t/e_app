<?php

namespace App\Interfaces\Http\Controllers\Web;

use App\Application\Services\SaleUnitService;
use App\Interfaces\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\SaleUnit\CreateSaleUnitRequest;
use App\Interfaces\Http\Requests\SaleUnit\UpdateSaleUnitRequest;

class SaleUnitController extends Controller
{
    public function __construct(private SaleUnitService $saleUnitService) {}

    public function index()
    {
        return view('sale-units.index');
    }

    public function create()
    {
        return view('sale-units.create');
    }

    public function store(CreateSaleUnitRequest $request)
    {
        $dto = $request->toDto();
        $this->saleUnitService->create($dto);

        return redirect()->route('sale-units.index')->with('success', 'Sale unit created successfully.');
    }

    public function edit(int $id)
    {
        $saleUnit = $this->saleUnitService->findById($id);

        return view('sale-units.edit', compact('saleUnit'));
    }

    public function update(int $id, UpdateSaleUnitRequest $request)
    {
        $dto = $request->toDto();
        $this->saleUnitService->update($dto, $id);

        return redirect()->route('sale-units.index')->with('success', 'Sale unit updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->saleUnitService->destroy($id);

        return redirect()->route('sale-units.index')->with('success', 'Sale unit deleted successfully.');
    }
}
