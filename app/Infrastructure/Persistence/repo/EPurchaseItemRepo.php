<?php
namespace App\Infrastructure\Persistence\repo;

use App\Application\Mapper\PurchaseItemMapper;
use App\Domain\Repo\PurchaseItemRepo;
use App\Infrastructure\Persistence\Models\PurchaseItem;


class EPurchaseItemRepo extends BaseERepo implements PurchaseItemRepo
{
    protected $modelClass = PurchaseItem::class;
    protected $mapper = PurchaseItemMapper::class;

    // protected $queryContext = ;

    protected array $searchFilters = [];

    protected array $withForPaginate = [];
    protected array $defaultRelationships = [];
}