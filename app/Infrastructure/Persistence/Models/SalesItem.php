<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bill_id',
        'product_id',
        'quantity',
        'price',
        'total'
    ];

    public function bill()
    {
        return $this->belongsTo(Sales::class, 'bill_id');
    }
}
