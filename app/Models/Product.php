<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'code', 'description', 'price', 'image', 'origin'];

    function stores()
    {
        return $this
            ->belongsToMany(Store::class)
            ->withPivot('quantity');
    }

    public static function generateCode()
    {
        do {
            $code = strtoupper(Str::random(5));
        } while (Product::where('code', $code)->exists());
        return $code;
    }
}
