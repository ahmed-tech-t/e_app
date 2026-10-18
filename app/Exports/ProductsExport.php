<?php

namespace App\Exports;

use App\Infrastructure\Persistence\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    const COLUMNS = [
        'code' => 'Code',
        'original_code' => 'Original Code',
        'name_ar' => 'Name (AR)',
        'name_en' => 'Name (EN)',
        'brand' => 'Brand',
        'origin' => 'Origin',
        'description' => 'Description',
        'units_per_carton' => 'Units Per Carton',
        'category' => 'Category',
        'sale_unit' => 'Sale Unit',
        'size' => 'Size',
    ];

    public function __construct(private array $columns = [], private bool $templateOnly = false) {}

    public function collection(): Collection
    {
        if ($this->templateOnly) {
            return collect();
        }

        return Product::with(['category', 'saleUnit'])->get();
    }

    public function headings(): array
    {
        $keys = $this->activeColumns();

        return array_values(array_intersect_key(self::COLUMNS, array_flip($keys)));
    }

    public function map($product): array
    {
        return array_map(fn ($col) => $this->value($product, $col), $this->activeColumns());
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    private function activeColumns(): array
    {
        return $this->columns ?: array_keys(self::COLUMNS);
    }

    private function value(Product $product, string $column): mixed
    {
        return match ($column) {
            'code' => $product->code,
            'original_code' => $product->original_code,
            'name_ar' => $product->name_ar,
            'name_en' => $product->name_en,
            'brand' => $product->brand,
            'origin' => $product->origin,
            'description' => $product->description,
            'units_per_carton' => $product->units_per_carton,
            'category' => $product->category?->name_ar,
            'sale_unit' => $product->saleUnit?->name_ar,
            'size' => $product->size,
            default => null,
        };
    }
}
