<?php
namespace App\Infrastructure\Persistence\repo;


use App\Application\Mapper\SalesReturnsMapper;
use App\Domain\Repo\SalesReturnsRepo;
use App\Infrastructure\Persistence\Models\SalesReturns;


class ESalesReturnsRepo extends BaseERepo implements SalesReturnsRepo
{
    protected $modelClass = SalesReturns::class;
    protected $mapper = SalesReturnsMapper::class;

    // protected $queryContext = ;

    protected array $searchFilters = [];

    protected array $withForPaginate = [];
    protected array $defaultRelationships = [];

    /**
     * @inheritDoc
     */
    public function findByOriginalBillId(int $id)
    {
        return $this->modelClass::where('original_bill_id', $id)->get();
    }

    /**
     * @inheritDoc
     */
    public function isThisFirstReturn(int $salesId): bool
    {
        return !$this->modelClass::where('original_bill_id', $salesId)->exists();
    }
}