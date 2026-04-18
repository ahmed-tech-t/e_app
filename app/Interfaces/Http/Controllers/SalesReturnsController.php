<?php

namespace App\Interfaces\Http\Controllers;
use App\Application\Services\SalesReturnsService;
use App\Interfaces\Http\Requests\SalesReturns\CreateSalesReturnsRequest;
use App\Interfaces\Http\Resources\SalesReturnsResource;




class SalesReturnsController extends BaseController
{
    protected string $resourceClass = SalesReturnsResource::class;
    protected string $storeRequest = CreateSalesReturnsRequest::class;


    public function __construct(private SalesReturnsService $salesReturnsService)
    {
        $this->service = $salesReturnsService;
    }

    public function preReturn()
    {
        $request = app($this->storeRequest);
        $entity = $this->service->preCreate($request->toDto());
        return $this->success(($this->resourceClass)::make($entity));
    }
}