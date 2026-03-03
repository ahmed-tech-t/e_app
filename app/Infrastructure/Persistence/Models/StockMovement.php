<?php

namespace App\Infrastructure\Persistence\Models;

use App\Infrastructure\Persistence\utils\StockMovementType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockMovement extends Model
{

    use SoftDeletes, HasFactory;
    protected $casts = [
        'type' => StockMovementType::class
    ];
    protected $fillable = [
        'product_batch_id',
        'location_id',
        'quantity',
        'type',
        'bill_number',
    ];

    public function batch()
    {
        return $this->belongsTo(ProductBatch::class, 'product_batch_id');
    }
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
