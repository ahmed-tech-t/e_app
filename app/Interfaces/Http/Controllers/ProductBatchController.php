<?php

namespace App\Interfaces\Http\Controllers;
use App\Application\Services\ProductBatchService;
use App\Domain\PagenatorMeta;
use App\Interfaces\Http\Requests\ProductBatch\CreateProductBatchRequest;
use App\Interfaces\Http\Requests\ProductBatch\SearchProductBatchRequest;
use App\Interfaces\Http\Requests\ProductBatch\UpdateProductBatchRequest;
use App\Interfaces\Http\Resources\ProductBatchResource;
use Illuminate\Http\Request;



class ProductBatchController extends BaseController
{
    protected string $resourceClass = ProductBatchResource::class;
    protected string $storeRequest = CreateProductBatchRequest::class;
    protected string $updateRequest = UpdateProductBatchRequest::class;



    public function __construct(private ProductBatchService $productBatchService)
    {
        $this->service = $productBatchService;
    }

    public function index(Request $request)
    {
        return parent::getPaginatedItems($request);
    }

    public function search(SearchProductBatchRequest $request)
    {
        $data = $request->validated();
        $productBatches = $this->productBatchService->search($data['batch_code']);
        return $this->success(data: ProductBatchResource::collection($productBatches));
    }
}