<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Testing\Fluent\Concerns\Has;

class ProductBatch extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'batch_code',
        'product_id',
        'remaining_quantity',
        'initial_quantity',
        'cost_price',
    ];

    public function locations()
    {
        return $this->belongsToMany(
            Location::class,
            'batch_locations'
        )
            ->withPivot('remaining_quantity')
            ->withTimestamps();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeWithLocationStock($query, $locationId, $productId)
    {
        return $query->join('batch_locations', 'product_batches.id', '=', 'batch_locations.product_batch_id')
            ->where('batch_locations.location_id', $locationId)
            ->where('product_batches.product_id', $productId)
            ->where('batch_locations.remaining_quantity', '>', 0)
        ;
    }
}
