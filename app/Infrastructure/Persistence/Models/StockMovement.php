<?php

namespace App\Infrastructure\Persistence\Models;

use App\Application\DTOs\Stock\web\StockMovementSearchDto;
use App\Infrastructure\Persistence\Pipeline\Filters\StockMovement\FilterByLocationId;

use App\Infrastructure\Persistence\Pipeline\Filters\StockMovement\FilterByType;
use App\Infrastructure\Persistence\Pipeline\Filters\StockMovement\StockMovementQueryContext;
use App\Infrastructure\Persistence\utils\StockMovementType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pipeline\Pipeline;


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


    public function productId(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->batch?->product_id,
        );
    }

    public function billUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (empty($this->bill_number)) {
                    return null;
                }

                if (str_starts_with($this->bill_number, 'PUR-')) {
                    $id = Purchase::where('code', $this->bill_number)->value('id');
                    return $id ? route('purchases.show', $id) : null;
                }

                if (str_starts_with($this->bill_number, 'SAL-')) {
                    $id = Sales::where('code', $this->bill_number)->value('id');
                    return $id ? route('sales.show', $id) : null;
                }

                if (str_starts_with($this->bill_number, 'RPU-')) {
                    $id = PurchaseReturn::where('code', $this->bill_number)->value('id');
                    return $id ? route('purchase-returns.show', $id) : null;
                }

                if (str_starts_with($this->bill_number, 'RSA-')) {
                    $id = SalesReturns::where('code', $this->bill_number)->value('id');
                    return $id ? route('sales-returns.show', $id) : null;
                }

                return null;
            }
        );
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
