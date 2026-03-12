<?php
namespace App\Infrastructure\Persistence\repo;

use App\Application\Mapper\PurchaseMapper;
use App\Domain\Repo\PurchaseRepo;
use App\Infrastructure\Persistence\Models\Purchase;


class EPurchaseRepo extends BaseERepo implements PurchaseRepo {
     protected $modelClass = Purchase::class;
     protected $mapper = PurchaseMapper::class; 

    // protected $queryContext = ;

    protected array $searchFilters = [];

    protected array $withForPaginate = [];
    protected array $defaultRelationships = [];
}