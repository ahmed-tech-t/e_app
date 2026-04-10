<?php

namespace App\Interfaces\Http\Controllers\Web;

use App\Application\Services\SupplierService;
use App\Interfaces\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Supplier\CreateSupplierRequest;

class SupplierController extends Controller
{
    public function __construct(private SupplierService $supplierService) {}

    public function index()
    {
        return view('suppliers.index');
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(CreateSupplierRequest $request)
    {
        $dto = $request->toDto();
        $this->supplierService->create($dto);

        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function edit(int $id)
    {
        $supplier = $this->supplierService->findById($id);

        return view('suppliers.edit', compact('supplier'));
    }

    public function update(int $id, CreateSupplierRequest $request)
    {
        $dto = $request->toDto();
        $this->supplierService->update($dto, $id);

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->supplierService->destroy($id);

        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
