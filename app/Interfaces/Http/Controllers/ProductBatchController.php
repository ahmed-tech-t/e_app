<?php

namespace App\Interfaces\Http\Controllers;
use App\Application\Services\ProductBatchService;
use App\Interfaces\Http\Requests\ProductBatch\CreateProductBatchRequest;
use App\Interfaces\Http\Requests\ProductBatch\UpdateProductBatchRequest;
use App\Interfaces\Http\Resources\ProductBatchResource;



class ProductBatchController extends BaseController
{
    protected string $resourceClass = ProductBatchResource::class;
    protected string $storeRequest = CreateProductBatchRequest::class;
    protected string $updateRequest = UpdateProductBatchRequest::class;



    public function __construct(private ProductBatchService $productBatchService)
    {
        $this->service = $productBatchService;
    }
}