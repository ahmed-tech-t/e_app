<?php

namespace App\Interfaces\Http\Controllers;

use App\Domain\PagenatorMeta;
use App\Interfaces\Http\Utils\RequestInterface;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

abstract class BaseController extends Controller
{
    use HttpResponses;

    // These must be defined in the child 
    protected $service;
    protected string $resourceClass;
    protected string $storeRequest;
    protected string $updateRequest;


    public function index()
    {
        $items = $this->service->findAll();
        return $this->success(($this->resourceClass)::collection($items));
    }

    // public function getPaginateditems(Request $request)
    // {
    //     // Using dynamic pagination defaults
    //     $perPage = $request->get('per_page', 10);

    //     $items = $this->service->getPaginateditems($perPage);
    //     $meta = new PagenatorMeta($items);

    //     return $this->success(
    //         data: ($this->resourceClass)::collection($items),
    //         meta: $meta->toArray()
    //     );
    // }

    public function store()
    {
        $request = app($this->storeRequest);
        // We assume the Request has a toDto() method
        $dto = $request->toDto();
        $entity = $this->service->create($dto);

        return $this->success(($this->resourceClass)::make($entity));
    }

    public function show(int $id)
    {
        $item = $this->service->findById($id);
        return $this->success(($this->resourceClass)::make($item));
    }


    public function update(int $id)
    {
        $request = app($this->updateRequest);
        $dto = $request->toDto();
        Log::info("BaseController", ['dto' => $dto]);
        $item = $this->service->update($dto, $id);
        return $this->success(($this->resourceClass)::make($item));
    }

    public function destroy(int $id)
    {
        $message = $this->service->destroy($id);
        return $this->success($message);
    }
}