<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase_item extends Model
{
    function product()
    {
        return $this->belongsTo(Product::class);
    }

    function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
