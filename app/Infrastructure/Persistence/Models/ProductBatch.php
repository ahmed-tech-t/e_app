<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductBatch extends Model
{
    use SoftDeletes;

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
}
