<?php

namespace App\Interfaces\Http\Controllers;
use App\Application\Services\PurchaseService;
use App\Interfaces\Http\Requests\Purchase\CreatePurchaseRequest;
use App\Interfaces\Http\Requests\Purchase\UpdatePurchaseRequest;
use App\Interfaces\Http\Resources\PurchaseResource;



class PurchaseController extends BaseController
{
    protected string $resourceClass = PurchaseResource::class;
    protected string $storeRequest = CreatePurchaseRequest::class;
    protected string $updateRequest = UpdatePurchaseRequest::class;


    public function __construct(private PurchaseService $purchaseService)
    {
        $this->service = $purchaseService;


    }

    public function prePurchase()
    {
        $request = app($this->storeRequest);
        $entity = $this->service->preCreate($request->toDto());
        return $this->success(($this->resourceClass)::make($entity));
    }
}