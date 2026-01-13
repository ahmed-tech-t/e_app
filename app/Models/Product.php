<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;


    function stores()
    {
        return $this
            ->belongsToMany(Store::class)
            ->withPivot('quantity');
    }
}
