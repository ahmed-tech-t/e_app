<?php

namespace App\Interfaces\Http\Controllers;
use App\Application\Services\SaleUnitService;
use App\Interfaces\Http\Requests\SaleUnit\CreateSaleUnitRequest;
use App\Interfaces\Http\Requests\SaleUnit\UpdateSaleUnitRequest;
use App\Interfaces\Http\Resources\SaleUnitResource;



class SaleUnitController extends BaseController
{
    protected string $resourceClass = SaleUnitResource::class;
    protected string $storeRequest = CreateSaleUnitRequest::class;
    protected string $updateRequest = UpdateSaleUnitRequest::class;


    public function __construct(private SaleUnitService $saleUnitService)
    {
        $this->service = $saleUnitService;
    }
}