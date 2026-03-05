<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Location extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'address',
        'phone',
        'type'
    ];

    public function patches()
    {
        return $this->belongsToMany(
            ProductBatch::class,
            'batch_locations'
        )
            ->withPivot('remaining_quantity')
            ->withTimestamps();
    }


    public function scopeFindProductLocations($query, $productCode)
    {
        $query = $this->join('batch_locations', 'locations.id', '=', 'batch_locations.location_id')
            ->join('product_batches', 'product_batches.id', '=', 'batch_locations.product_batch_id')
            ->join('products', 'products.id', '=', 'product_batches.product_id')
            ->where('products.code', $productCode)
            ->select(
                'locations.id',
                'locations.name',
                DB::raw('SUM(batch_locations.remaining_quantity) as total_qty')
            )
            ->groupBy('locations.id', 'locations.name')
            ->having('total_qty', '>', 0);
        return $query;
    }
}
