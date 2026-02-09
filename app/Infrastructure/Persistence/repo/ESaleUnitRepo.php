<?php
namespace App\Infrastructure\Persistence\repo;

use App\Application\Mapper\SaleUnitMapper;
use App\Domain\Repo\SaleUnitRepo;
use App\Infrastructure\Persistence\Models\SaleUnit;


class ESaleUnitRepo extends BaseERepo implements SaleUnitRepo {
     protected $modelClass = SaleUnit::class;
     protected $mapper = SaleUnitMapper::class;
}