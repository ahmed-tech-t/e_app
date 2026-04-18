<?php

namespace App\Infrastructure\Persistence\Models;

use App\Domain\Utils\ReturnedItemStatus;
use Illuminate\Database\Eloquent\Model;


class SalesReturnItem extends Model
{

    protected $fillable = [
        'sales_return_id',
        'product_id',
        'quantity',
        'reason',
        'restock_status',
        'price',
        'total',
    ];
    protected $casts = [
        'restock_status' => ReturnedItemStatus::class,
    ];
    public function salesReturn()
    {
        return $this->belongsTo(SalesReturns::class, 'sales_return_id');
    }
}
