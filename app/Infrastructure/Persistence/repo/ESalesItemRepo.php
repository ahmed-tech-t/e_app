<?php
namespace App\Infrastructure\Persistence\repo;

use App\Application\Mapper\SalesItemMapper;
use App\Domain\Repo\SalesItemRepo;
use App\Infrastructure\Persistence\Models\SalesItem;


class ESalesItemRepo extends BaseERepo implements SalesItemRepo {
     protected $modelClass = SalesItem::class;
     protected $mapper = SalesItemMapper::class; 

    // protected $queryContext = ;

    protected array $searchFilters = [];

    protected array $withForPaginate = [];
    protected array $defaultRelationships = [];
}