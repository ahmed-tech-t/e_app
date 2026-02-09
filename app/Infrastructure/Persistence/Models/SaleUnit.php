<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleUnit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name_ar',
        'name_en'
    ];
}
