<?php

namespace App\Application\Services;

use App\Domain\Entities\LocationEntity;
use App\Domain\Repo\LocationRepo;

class LocationService extends BaseService
{
    protected string $entityClass = LocationEntity::class;

    public function __construct(LocationRepo $repo)
    {
        $this->repo = $repo;
    }

    public function productLocations(string $code)
    {
        return $this->repo->productLocations($code);
    }
}