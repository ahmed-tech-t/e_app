<?php
namespace App\Infrastructure\Persistence\repo;

use App\Application\Mapper\SupplierMapper;
use App\Domain\Repo\SupplierRepo;
use App\Infrastructure\Persistence\Models\Supplier;


class ESupplierRepo extends BaseERepo implements SupplierRepo
{
    protected $modelClass = Supplier::class;
    protected $mapper = SupplierMapper::class;

    // protected $queryContext = ;

    protected array $searchFilters = [];

    protected array $withForPaginate = [];
    protected array $defaultRelationships = [];
}