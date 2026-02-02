<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Mapper\ProductMapper;
use App\Application\UseCases\Product\CreateProductUseCase;
use App\Application\UseCases\Product\DeleteProductUseCase;
use App\Application\UseCases\Product\GetAllProductUseCase;
use App\Application\UseCases\Product\GetProductByIdUseCase;
use App\Application\UseCases\Product\UpdateProductUseCase;
use App\Domain\PagenatorMeta;
use App\Interfaces\Http\Requests\CreateProductRequest;
use App\Interfaces\Http\Requests\GetAllProductsRequest;
use App\Interfaces\Http\Requests\UpdateProductRequest;
use App\Interfaces\Http\Resources\ProductResource;
use App\Traits\HttpResponses;

class ProductController extends Controller
{

    use HttpResponses;
    /**
     * Display a listing of the resource.
     */

    public function __construct(
        private CreateProductUseCase $createProductUseCase,
        private GetAllProductUseCase $getAllProductUseCase,
        private GetProductByIdUseCase $getProductByIdUseCase,
        private UpdateProductUseCase $updateProductUseCase,
        private DeleteProductUseCase $deleteProductUseCase
    ) {
    }



    public function index(GetAllProductsRequest $request)
    {
        $request->validated();
        $products = $this->getAllProductUseCase->execute($request['page'], $request['per_page']);
        $meta = new PagenatorMeta($products);
        return $this->success(data: ProductResource::collection($products), meta: $meta->toArray());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProductRequest $request)
    {
        $dto = $request->toDto();
        $productEntity = $this->createProductUseCase->execute($dto);
        return $this->success(ProductResource::make($productEntity));
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $product = $this->getProductByIdUseCase->execute($id);
        return $this->success(ProductResource::make($product));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, int $id)
    {
        $dto = $request->toDto();
        $product = $this->updateProductUseCase->execute($dto, $id);
        return $this->success(ProductResource::make($product));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        return $this->success($this->deleteProductUseCase->execute($id));
    }
}
