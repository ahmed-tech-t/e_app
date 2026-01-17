<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductRequest;
use App\Http\Requests\GetAllProductsRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index(GetAllProductsRequest $request, ProductService $service)
    {
        $request->validated();

        $products = $service->
            getPaginatedProducts(
                $request->page,
                $request->per_page
            );

        return $this->success(
            ProductResource::collection($products)->response()->getData(true),
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddProductRequest $request)
    {
        $data = $request->validated();
        $data['code'] = Product::generateCode();
        $Product = Product::create($data);
        return $this->success(ProductResource::make($Product));
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return $this->success(ProductResource::make($product), title: 'product');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $product->update($data);
        return $this->success(ProductResource::make($product));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return $this->success("Product deleted successfully");
    }
}
