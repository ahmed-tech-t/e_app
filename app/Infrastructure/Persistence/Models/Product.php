<?php

namespace App\Infrastructure\Persistence\Models;

use App\Infrastructure\Persistence\utils\PriceType;
use Database\Factories\ProductFactory;
use Illuminate\Bus\Batch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'original_code',
        'category_id',
        'name_ar',
        'name_en',
        'code',
        'description',
        'brand',
        'image',
        'origin',
        'sale_unit_id',
        'units_per_carton'
    ];

    protected $casts = [
        'id' => 'integer',
        'retail_price' => 'float',
        'wholesale_price' => 'float',
        'units_per_carton' => 'integer',
    ];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function productBatches(): HasMany
    {
        return $this->hasMany(ProductBatch::class);
    }
    public function saleUnit(): BelongsTo
    {
        return $this->belongsTo(SaleUnit::class);
    }


    public function prices()
    {
        return $this->hasMany(ProductPrice::class);
    }



    // override factory
    protected static function newFactory()
    {
        return ProductFactory::new();
    }


    public function scopeWithPrices($query)
    {
        return $query
            ->addSelect([
                'retail_price' =>
                    ProductPrice::select('price')
                        ->whereColumn('product_id', 'products.id')
                        ->where('type', PriceType::RETAIL)
                        ->whereNull('valid_to')
                        ->limit(1),
                'wholesale_price' =>
                    ProductPrice::select('price')
                        ->whereColumn('product_id', 'products.id')
                        ->where('type', PriceType::WHOLESALE)
                        ->whereNull('valid_to')
                        ->limit(1),
            ]);
    }

    public function scopeWithQuantityAt($query, $locationId)
    {
        return $query->addSelect([
            'quantity' =>
                ProductBatch::query()
                    ->join('batch_locations', 'product_batches.id', '=', 'batch_locations.product_batch_id')
                    ->selectRaw('SUM(batch_locations.remaining_quantity)')
                    ->whereColumn('product_batches.product_id', 'products.id')
                    ->where('batch_locations.location_id', $locationId)
        ]);
    }

    public function scopeOnlyAvailableAt($query, $locationId)
    {
        return $query->whereHas('productBatches.locations', function ($q) use ($locationId) {
            $q->where('location_id', $locationId)
                ->where('remaining_quantity', '>', 0);
        });
    }

    public function scopeAllProducts($query)
    {
        return $query
            ->select('products.*')
            ->withPrices();
    }

    public function scopeAtLocation($query, $locationId, $filterZeroStock = true)
    {
        $query
            ->allProducts()
            ->withQuantityAt($locationId);
        if ($filterZeroStock)
            $query->OnlyAvailableAt($locationId);
        return $query;
    }


    public function scopeWithLocationContext()
    {

    }
}
