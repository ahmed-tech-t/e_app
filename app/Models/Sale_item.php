<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale_item extends Model
{
    function product()
    {
        return $this->belongsTo(Product::class);
    }

    function sale()
    {
        return $this->belongsTo(Sale::class);
    }

}
