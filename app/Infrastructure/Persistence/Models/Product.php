<?php

namespace App\Infrastructure\Persistence\Models;

use App\Infrastructure\Persistence\utils\PriceType;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function productBatches(): BelongsToMany
    {
        return $this->belongsToMany(ProductBatch::class);
    }
    public function saleUnit(): BelongsTo
    {
        return $this->belongsTo(SaleUnit::class);
    }


    public function prices()
    {
        return $this->hasMany(ProductPrice::class);
    }
    public function retailPrice()
    {
        return $this->hasOne(ProductPrice::class)
            ->where('type', PriceType::RETAIL)
            ->whereNull('valid_to');
    }
    public function wholesalePrice()
    {
        return $this->hasOne(ProductPrice::class)
            ->where('type', PriceType::WHOLESALE)
            ->whereNull('valid_to');
    }


    // override factory
    protected static function newFactory()
    {
        return ProductFactory::new();
    }

    public function scopeWithLocationStock($query, $locationId)
    {
        return $query
            ->join('product_batches', 'products.id', '=', 'product_batches.product_id')
            ->join('batch_locations', 'product_batches.id', '=', 'batch_locations.product_batch_id')
            ->join('product_prices', 'products.id', '=', 'product_prices.product_id')
            ->whereNull('product_prices.valid_to')

            ->where('batch_locations.location_id', $locationId)
            ->where('product_batches.remaining_quantity', '>', 0)

            ->groupBy('products.id')

            ->select([
                'products.*',
                'product_prices.price where product_prices.type = "retail" as retail_price',
                'product_prices.price where product_prices.type = "wholesale" as wholesale_price'
            ])
            ->selectRaw('SUM(batch_locations.remaining_quantity) as total_remaining_quantity');
    }
}
