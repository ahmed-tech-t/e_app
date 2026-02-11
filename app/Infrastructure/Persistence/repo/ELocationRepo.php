<?php
namespace App\Infrastructure\Persistence\repo;

use App\Application\Mapper\LocationMapper;
use App\Domain\Repo\LocationRepo;
use App\Infrastructure\Persistence\Models\Location;


class ELocationRepo extends BaseERepo implements LocationRepo
{
     protected $modelClass = Location::class;
     protected $mapper = LocationMapper::class;

     //fifo
     public function getFirstLocation()
     {

     }
}