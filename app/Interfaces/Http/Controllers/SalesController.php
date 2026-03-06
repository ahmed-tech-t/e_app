<?php

namespace App\Interfaces\Http\Controllers;
use App\Application\Services\SalesService;
use App\Interfaces\Http\Requests\Sales\CreateSalesRequest;
use App\Interfaces\Http\Requests\Sales\UpdateSalesRequest;
use App\Interfaces\Http\Resources\SalesResource;



class SalesController extends BaseController
{
    protected string $resourceClass = SalesResource::class;
    protected string $storeRequest = CreateSalesRequest::class;
    protected string $updateRequest = UpdateSalesRequest::class;


    public function __construct(private SalesService $salesService)
    {
        $this->service = $salesService;
    }
}