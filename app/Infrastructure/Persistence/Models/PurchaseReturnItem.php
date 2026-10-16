<?php

namespace App\Infrastructure\Persistence\Models;

use App\Domain\Utils\ReturnedItemStatus;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturnItem extends Model
{
    protected $fillable = [
        'purchase_return_id',
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

    public function purchaseReturn()
    {
        return $this->belongsTo(PurchaseReturn::class, 'purchase_return_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
