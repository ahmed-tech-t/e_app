<?php

namespace App\Interfaces\Http\Controllers\Web;

use App\Application\Services\CategoryService;
use App\Application\Services\ProductPriceService;
use App\Application\Services\ProductService;
use App\Application\Services\SaleUnitService;
use App\Interfaces\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Product\CreateProductRequest;
use App\Interfaces\Http\Requests\Product\UpdateProductRequest;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private CategoryService $categoryService,
        private SaleUnitService $saleUnitService,
        private ProductPriceService $productPriceService
    ) {
    }

    public function index()
    {
        return view('products.index');
    }

    public function create()
    {
        $categories = $this->categoryService->findAll();
        $saleUnits = $this->saleUnitService->findAll();

        return view('products.create', compact('categories', 'saleUnits'));
    }

    public function store(CreateProductRequest $request)
    {
        $dto = $request->toDto();
        $this->productService->create($dto);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function show(int $id)
    {
        $product = $this->productService->findById($id);
        $priceHistory = $this->productPriceService->getProductPriceHistory($id, null);
        Log::info('Price history for product ID ' . $id, ['priceHistory' => $priceHistory]);

        return view('products.show', compact('product', 'priceHistory'));
    }

    public function edit(int $id)
    {
        $product = $this->productService->findById($id);
        $categories = $this->categoryService->findAll();
        $saleUnits = $this->saleUnitService->findAll();

        return view('products.edit', compact('product', 'categories', 'saleUnits'));
    }

    public function update(int $id, UpdateProductRequest $request)
    {
        $dto = $request->toDto();
        $this->productService->update($dto, $id);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->productService->destroy($id);

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
