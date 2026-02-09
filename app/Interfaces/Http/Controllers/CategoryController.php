<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Services\CategoryService;
use App\Interfaces\Http\Resources\CategoryResource;
use App\Interfaces\Http\Requests\Category\CreateCategoryRequest;
use App\Interfaces\Http\Requests\Category\UpdateCategoryRequest;

class CategoryController extends BaseController
{
    protected string $resourceClass = CategoryResource::class;
    protected string $storeRequest = CreateCategoryRequest::class;
    protected string $updateRequest = UpdateCategoryRequest::class;

    public function __construct(private CategoryService $categoryService)
    {
        $this->service = $categoryService;
    }
}