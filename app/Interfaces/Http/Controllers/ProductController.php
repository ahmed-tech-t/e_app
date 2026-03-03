<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Services\ProductService;
use App\Domain\PaginatorMeta;
use App\Infrastructure\Persistence\utils\Constants;
use App\Interfaces\Http\Requests\Product\CreateProductRequest;
use App\Interfaces\Http\Requests\Product\ProductSearchRequest;
use App\Interfaces\Http\Requests\Product\UpdateProductRequest;
use App\Interfaces\Http\Resources\ProductResource;
use Illuminate\Http\Request;

class ProductController extends BaseController
{

    protected string $resourceClass = ProductResource::class;
    protected string $searchRequest = ProductSearchRequest::class;
    protected string $storeRequest = CreateProductRequest::class;
    protected string $updateRequest = UpdateProductRequest::class;

    public function __construct(
        private ProductService $productService,
    ) {
        $this->service = $productService;
    }


    public function findAllByLocation(Request $request, int $locationId)
    {
        $perPage = $request->input('per_page', Constants::DEFAULT_PER_PAGE);
        $items = $this->service->findAllByLocation($locationId, $perPage);
        $meta = new PaginatorMeta($items);
        return $this->success(
            data: ($this->resourceClass)::collection($items),
            meta: $meta->toArray()
        );
    }

    public function findByLocation(int $productId, int $locationId)
    {
        $product = $this->service->findByLocation($productId, $locationId);
        return $this->success(
            data: ($this->resourceClass)::make($product)
        );
    }
}