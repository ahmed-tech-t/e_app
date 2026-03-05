<?php
namespace App\Infrastructure\Persistence\repo;

use App\Application\Mapper\LocationMapper;
use App\Domain\Repo\LocationRepo;
use App\Infrastructure\Persistence\Models\Location;

use function PHPSTORM_META\map;

class ELocationRepo extends BaseERepo implements LocationRepo
{
     protected $modelClass = Location::class;
     protected $mapper = LocationMapper::class;

     //fifo
     public function ProductLocations($productCode)
     {
          return Location::findProductLocations($productCode)->get()->map(fn($model) => ($this->mapper)::toEntity($model));
     }
}