<?php

namespace App\Infrastructure\Persistence\repo;

use App\Application\Mapper\PurchaseReturnMapper;
use App\Domain\Repo\PurchaseReturnRepo;
use App\Infrastructure\Persistence\Models\PurchaseReturn;

class EPurchaseReturnRepo extends BaseERepo implements PurchaseReturnRepo
{
    protected $modelClass = PurchaseReturn::class;

    protected $mapper = PurchaseReturnMapper::class;

    protected array $searchFilters = [];

    protected array $withForPaginate = ['items.product', 'purchase'];

    protected array $defaultRelationships = ['items.product', 'purchase'];

    public function findByOriginalBillId(int $id)
    {
        return $this->modelClass::where('original_bill_id', $id)->get();
    }

    public function isThisFirstReturn(int $purchaseId): bool
    {
        return ! $this->modelClass::where('original_bill_id', $purchaseId)->exists();
    }
}
