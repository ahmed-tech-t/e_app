<?php

namespace App\Infrastructure\Persistence\Models;

use App\Infrastructure\Persistence\utils\PriceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductPrice extends Model
{
    use SoftDeletes;

    protected $casts = [
        'type' => PriceType::class,
    ];
    protected $fillable = [
        'product_id',
        'type',
        'price',
        'valid_from',
        'valid_to',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
