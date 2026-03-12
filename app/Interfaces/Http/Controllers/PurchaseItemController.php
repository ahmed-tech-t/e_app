<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Services\PurchaseItemService;
use App\Interfaces\Http\Requests\PurchaseItem\CreatePurchaseItemRequest;
use App\Interfaces\Http\Requests\PurchaseItem\UpdatePurchaseItemRequest;
use App\Interfaces\Http\Resources\PurchaseItemResource;



class PurchaseItemController extends BaseController
{
    protected string $resourceClass = PurchaseItemResource::class;
    protected string $storeRequest = CreatePurchaseItemRequest::class;
    protected string $updateRequest = UpdatePurchaseItemRequest::class;

    public function __construct(private PurchaseItemService $purchase_itemService)
    {
        $this->service = $purchase_itemService;
    }
}