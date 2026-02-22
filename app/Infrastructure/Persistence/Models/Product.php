<?php

namespace App\Infrastructure\Persistence\Models;

use App\Infrastructure\Persistence\utils\PriceType;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
}
