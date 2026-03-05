<?php

namespace App\Interfaces\Http\Controllers;
use App\Application\Services\LocationService;
use App\Interfaces\Http\Requests\Location\CreateLocationRequest;
use App\Interfaces\Http\Requests\Location\UpdateLocationRequest;
use App\Interfaces\Http\Resources\LocationResource;
use App\Utils\ValidationRules;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Validator;



class LocationController extends BaseController
{
    protected string $resourceClass = LocationResource::class;
    protected string $storeRequest = CreateLocationRequest::class;
    protected string $updateRequest = UpdateLocationRequest::class;


    public function __construct(private LocationService $locationService)
    {
        $this->service = $locationService;
    }

    public function productLocations(string $code)
    {

        $clearCode = strip_tags($code);
        $validatedData = Validator::make(['code' => $clearCode], [
            'code' => ValidationRules::code() . '|exists:products,code',
        ])->validate();

        $result = $this->locationService->productLocations($validatedData['code']);
        return $this->success(
            data: ($this->resourceClass)::collection($result)
        );
    }
}