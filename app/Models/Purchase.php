<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{

    function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    function items()
    {
        return $this->hasMany(Purchase_item::class);
    }
}
