<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    function products()
    {
        return $this->hasMany(Sale_item::class);
    }

    function store()
    {
        return $this->belongsTo(Store::class);
    }
}
