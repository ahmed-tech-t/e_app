<?php

namespace App\Interfaces\Http\Controllers\Web;

use App\Application\Services\CategoryService;
use App\Application\Services\ProductPriceService;
use App\Application\Services\ProductService;
use App\Application\Services\SaleUnitService;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use App\Interfaces\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Product\CreateProductRequest;
use App\Interfaces\Http\Requests\Product\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

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
        return view('products.index', ['columnAliases' => ProductsImport::COLUMN_ALIASES]);
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

    public function exportPage()
    {
        return view('products.export', ['columns' => ProductsExport::COLUMNS]);
    }

    public function exportDownload(Request $request)
    {
        $columns = $request->input('columns', array_keys(ProductsExport::COLUMNS));

        return Excel::download(new ProductsExport($columns), 'products.xlsx');
    }

    public function exportTemplate()
    {
        return Excel::download(new ProductsExport(templateOnly: true), 'products-template.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv|max:10240']);

        $import = new ProductsImport;
        Excel::import($import, $request->file('file'));

        $failures = $import->failures();
        if (!empty($failures)) {
            $shown   = array_slice($failures, 0, 2);
            $rest    = count($failures) - count($shown);
            $message = implode("\n", $shown);
            if ($rest > 0) {
                $message .= "\n(and {$rest} more skipped row(s))";
            }
            return back()->with('error', $message);
        }

        return redirect()->route('products.index')->with('success', 'Products imported successfully.');
    }
}
