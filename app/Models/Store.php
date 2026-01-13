<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    function products()
    {
        return $this
            ->belongsToMany(Product::class)
            ->withPivot('quantity');
    }

    function sales()
    {
        return $this->hasMany(Sale::class);
    }

    function purchases()
    {
        return $this->hasMany(Purchase::class);
    }


}
