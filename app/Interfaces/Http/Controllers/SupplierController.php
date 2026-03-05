<?php

namespace App\Interfaces\Http\Controllers;
use App\Application\Services\SupplierService;
use App\Interfaces\Http\Requests\Supplier\CreateSupplierRequest;
use App\Interfaces\Http\Requests\Supplier\UpdateSupplierRequest;
use App\Interfaces\Http\Resources\SupplierResource;
use Illuminate\Http\Request;

class SupplierController extends BaseController
{
    protected string $resourceClass = SupplierResource::class;
    protected string $storeRequest = CreateSupplierRequest::class;
    protected string $updateRequest = UpdateSupplierRequest::class;

    public function __construct(private SupplierService $supplierService)
    {
        $this->service = $supplierService;
    }

    public function index(Request $request)
    {
        return $this->getPaginatedItems($request);
    }
}