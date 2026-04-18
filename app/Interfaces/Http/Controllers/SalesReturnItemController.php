<?php

namespace App\Interfaces\Http\Controllers;
use App\Application\Services\SalesReturnItemService;
use App\Interfaces\Http\Requests\SalesReturnItem\CreateSalesReturnItemRequest;
use App\Interfaces\Http\Requests\SalesReturnItem\UpdateSalesReturnItemRequest;
use App\Interfaces\Http\Resources\SalesReturnItemResource;



class SalesReturnItemController extends BaseController
{
    protected string $resourceClass = SalesReturnItemResource::class;
    protected string $storeRequest = CreateSalesReturnItemRequest::class;
    protected string $updateRequest = UpdateSalesReturnItemRequest::class;


    public function __construct(private SalesReturnItemService $salesReturnItemService)
    {
        $this->service = $salesReturnItemService;
    }
}