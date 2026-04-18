<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'store_id',
        'customer_name',
        'customer_phone',
        'total',
        'discount',
        'tax',
        'grand_total',
    ];

    public function items()
    {
        return $this->hasMany(SalesItem::class, 'bill_id');
    }
}
