<?php

namespace App\Imports;

use App\Infrastructure\Persistence\Models\Category;
use App\Infrastructure\Persistence\Models\Product;
use App\Infrastructure\Persistence\Models\SaleUnit;
use App\Traits\CodeGenerator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    use CodeGenerator;

    const COLUMN_ALIASES = [
        'code' => ['code', 'product_code', 'item_code', 'sku', 'prod_code'],
        'original_code' => ['original_code', 'orig_code', 'original'],
        'name_ar' => ['name_ar', 'arabic_name', 'name_arabic'],
        'name_en' => ['name_en', 'english_name', 'name_english', 'name'],
        'brand' => ['brand', 'brand_name', 'make'],
        'origin' => ['origin', 'country', 'country_of_origin', 'made_in'],
        'description' => ['description', 'desc', 'details', 'notes'],
        'units_per_carton' => ['units_per_carton', 'units', 'carton_units', 'qty_per_carton'],
        'category' => ['category', 'category_name', 'cat'],
        'sale_unit' => ['sale_unit', 'unit', 'selling_unit', 'uom'],
        'size' => ['size', 'product_size', 'volume', 'weight'],
    ];

    const REQUIRED_FIELDS = ['original_code', 'name_ar', 'category', 'sale_unit'];

    private array $failures = [];

    public function model(array $row): ?Product
    {
        $data = $this->resolveRow($row);

        foreach (self::REQUIRED_FIELDS as $field) {
            if (empty($data[$field])) {
                $this->failures[] = "Skipped row: missing required field '{$field}'. Recognised columns: ".implode(', ', array_keys($data));

                return null;
            }
        }

        $categoryId = Category::where('name_ar', $data['category'])->value('id');
        $saleUnitId = SaleUnit::where('name_ar', $data['sale_unit'])->value('id');

        $product = Product::where('original_code', $data['original_code'])->first()
                   ?? new Product;

        if (! $product->exists) {
            do {
                $code = self::generateCode('PRO');
            } while (Product::where('code', $code)->exists());

            $product->code = $code;
            $product->image = 'images/store.png';
        }

        $product->fill([
            'original_code' => $data['original_code'],
            'name_ar' => $data['name_ar'],
            'name_en' => $data['name_en'] ?? null,
            'brand' => $data['brand'] ?? null,
            'origin' => $data['origin'] ?: 'China',
            'description' => $data['description'] ?? null,
            'units_per_carton' => $data['units_per_carton'] ?? 1,
            'category_id' => $categoryId,
            'sale_unit_id' => $saleUnitId,
            'size' => $data['size'] ?? null,
        ]);

        return $product;
    }

    public function failures(): array
    {
        return $this->failures;
    }

    private function resolveRow(array $row): array
    {
        $first20 = array_slice($row, 0, 20, true);
        $resolved = [];

        foreach (self::COLUMN_ALIASES as $field => $aliases) {
            foreach ($aliases as $alias) {
                if (array_key_exists($alias, $first20) && $first20[$alias] !== null && $first20[$alias] !== '') {
                    $resolved[$field] = $first20[$alias];
                    break;
                }
            }
        }

        return $resolved;
    }
}
