<?php
namespace App\Infrastructure\Persistence\repo;

use App\Application\Mapper\SalesMapper;
use App\Domain\Repo\SalesRepo;
use App\Infrastructure\Persistence\Models\Sales;


class ESalesRepo extends BaseERepo implements SalesRepo {
     protected $modelClass = Sales::class;
     protected $mapper = SalesMapper::class; 

    // protected $queryContext = ;

    protected array $searchFilters = [];

    protected array $withForPaginate = [];
    protected array $defaultRelationships = [];
}