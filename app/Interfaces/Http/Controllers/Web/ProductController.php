<?php

namespace App\Interfaces\Http\Controllers\Web;

use App\Application\Services\CategoryService;
use App\Application\Services\ProductCatalogService;
use App\Application\Services\ProductPriceService;
use App\Application\Services\ProductService;
use App\Application\Services\SaleUnitService;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use App\Infrastructure\Persistence\Models\Product;
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
        private ProductPriceService $productPriceService,
        private ProductCatalogService $productCatalogService
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

    public function catalogPage(Request $request)
    {
        $categories = $this->categoryService->findAll();
        $brands = $this->productCatalogService->getBrands();
        $products = Product::with(['category', 'saleUnit'])
            ->withPrices()
            ->orderBy('name_ar')
            ->get();

        $productsData = $products->map(fn ($p) => [
            'id' => $p->id,
            'code' => $p->code,
            'name_ar' => $p->name_ar,
            'name_en' => $p->name_en ?? '',
            'brand' => $p->brand ?? '',
            'category_id' => $p->category_id,
            'category_name' => $p->category->name_ar ?? ($p->category->name_en ?? '-'),
            'retail_price' => (float) ($p->retail_price ?? 0),
            'wholesale_price' => (float) ($p->wholesale_price ?? 0),
            'image' => $p->image,
        ])->values()->all();

        return view('products.catalog', compact('categories', 'brands', 'productsData'));
    }

    public function catalogDownload(Request $request)
    {
        @ini_set('memory_limit', '512M');
        @set_time_limit(300);

        $request->validate([
            'category_id' => 'nullable|integer',
            'brand' => 'nullable|string|max:255',
            'select_all' => 'nullable|boolean',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'integer',
            'price_type' => 'nullable|in:retail,wholesale,both',
        ]);

        $categoryId = $request->filled('category_id') ? (int) $request->input('category_id') : null;
        $brand = $request->filled('brand') ? (string) $request->input('brand') : null;
        $selectAll = $request->boolean('select_all', true);
        $productIds = $selectAll ? null : $request->input('product_ids', []);
        $priceType = $request->input('price_type', 'retail');

        $products = $this->productCatalogService->getProductsForCatalog($categoryId, $brand, $productIds);

        if ($products->isEmpty()) {
            return back()->with('error', __('app.products.no_products_for_catalog') ?? 'No products found for the selected criteria.');
        }

        $pdf = $this->productCatalogService->generatePdf($products, $priceType);
        $filename = 'catalog_' . date('Y-m-d_His') . '.pdf';

        return $pdf->download($filename);
    }
}
