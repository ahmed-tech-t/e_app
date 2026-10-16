<?php
namespace App\Infrastructure\Persistence\repo;

use App\Application\Mapper\SalesMapper;
use App\Domain\Repo\SalesRepo;
use App\Infrastructure\Persistence\Models\Sales;


class ESalesRepo extends BaseERepo implements SalesRepo
{
    protected $modelClass = Sales::class;
    protected $mapper = SalesMapper::class;

    // protected $queryContext = ;

    protected array $searchFilters = [];

    protected array $withForPaginate = ['items.product'];
    protected array $defaultRelationships = ['items.product'];
}