<?php

namespace App\Interfaces\Http\Controllers;
use App\Application\Services\LocationService;
use App\Interfaces\Http\Requests\Location\CreateLocationRequest;
use App\Interfaces\Http\Requests\Location\UpdateLocationRequest;
use App\Interfaces\Http\Resources\LocationResource;



class LocationController extends BaseController
{
    protected string $resourceClass = LocationResource::class;
    protected string $storeRequest = CreateLocationRequest::class;
    protected string $updateRequest = UpdateLocationRequest::class;


    public function __construct(private LocationService $locationService)
    {
        $this->service = $locationService;
    }
}