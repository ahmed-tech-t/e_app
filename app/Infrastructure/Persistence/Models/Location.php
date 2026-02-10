<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'address',
        'phone',
        'type'
    ];

    public function patches()
    {
        return $this->belongsToMany(
            ProductBatch::class,
            'batch_location'
        )
            ->withPivot('remaining_quantity')
            ->withTimestamps();
    }
}
