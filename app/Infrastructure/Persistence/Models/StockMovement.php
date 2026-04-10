<?php

namespace App\Infrastructure\Persistence\Models;

use App\Application\DTOs\Stock\web\StockMovementSearchDto;
use App\Infrastructure\Persistence\Pipeline\Filters\StockMovement\FilterByLocationId;

use App\Infrastructure\Persistence\Pipeline\Filters\StockMovement\FilterByType;
use App\Infrastructure\Persistence\Pipeline\Filters\StockMovement\StockMovementQueryContext;
use App\Infrastructure\Persistence\utils\StockMovementType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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

    public function scopeWithSearchByType($query, $type)
    {
        return $query->with('location')->where('type', $type);
    }


    public function scopeWithProductName($query, $productName)
    {

        $query->whereHas('batch.product', function ($q) use ($productName) {
            $q->withTrashed();
            $q->where('name_ar', 'like', '%' . $productName . '%')
                ->orWhere('name_en', 'like', '%' . $productName . '%');
        });
    }

    public function scopeWithBillNumber($query, $billNumber)
    {
        return $query->where('bill_number', 'like', '%' . $billNumber . '%');
    }

    public function scopeWithSearch($query, StockMovementSearchDto $dto)
    {
        $query->withTrashed();
        $context = StockMovementQueryContext::create($query, $dto);

        $query->when($dto->search, fn($q) => $q->applySmartSearch($dto->search));

        $context = app(Pipeline::class)
            ->send($context)
            ->through([
                FilterByLocationId::class,
                FilterByType::class,
            ])
            ->thenReturn();

        return $context->query
            ->with([
                'location' => fn($q) => $q->withTrashed(),
                'batch.product' => fn($q) => $q->withTrashed()
            ])
            ->orderBy('created_at', 'desc');

    }

    public function scopeApplySmartSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->withBillNumber($search)
                ->orWhere(function ($orQ) use ($search) {
                    $orQ->withProductName($search);
                });
        });
    }
}
