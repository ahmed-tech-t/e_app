<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Services\CategoryService;
use App\Interfaces\Http\Resources\CategoryResource;
use App\Traits\HttpResponses;
use App\Interfaces\Http\Requests\Category\CreateCategoryRequest;
use App\Interfaces\Http\Requests\Category\UpdateCategoryRequest;

class CategoryController extends Controller
{
    use HttpResponses;
    public function __construct(private CategoryService $service)
    {
    }

    public function index()
    {
        return $this->success(CategoryResource::collection($this->service->findAll()));
    }


    public function store(CreateCategoryRequest $request)
    {
        $entity = $this->service->create($request->toDto());
        return $this->success(CategoryResource::make($entity));
    }

    public function show(int $id)
    {
        return $this->success(CategoryResource::make($this->service->findById($id)));
    }

    public function update(UpdateCategoryRequest $request, int $id)
    {
        $entity = $this->service->update($request->toDto(), $id);
        return $this->success(CategoryResource::make($entity));
    }

    public function destroy(int $id)
    {
        return $this->success($this->service->destory($id));
    }




}