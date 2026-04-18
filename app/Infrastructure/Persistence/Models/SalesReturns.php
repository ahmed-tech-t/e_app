<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;

class SalesReturns extends Model
{

    protected $fillable = [
        'code',
        'location_id',
        'original_bill_id',
        'total',
        'discount',
        'tax',
        'grand_total',
    ];

    public function items()
    {
        return $this->hasMany(SalesReturnItem::class, 'sales_return_id');
    }
}
