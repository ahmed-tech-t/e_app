<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Services\SalesService;
use App\Interfaces\Http\Requests\Sales\CreateSalesRequest;
use App\Interfaces\Http\Resources\SalesResource;
use Illuminate\Http\Request;

class SalesController extends BaseController
{
    protected string $resourceClass = SalesResource::class;

    protected string $storeRequest = CreateSalesRequest::class;

    public function __construct(private SalesService $salesService)
    {
        $this->service = $salesService;
    }

    public function index(Request $request)
    {
        return $this->getPaginatedItems($request);
    }

    public function preSale()
    {
        $request = app($this->storeRequest);
        $entity = $this->service->preCreate($request->toDto());

        return $this->success(($this->resourceClass)::make($entity));
    }
}
