<?php

namespace App\Interfaces\Http\Controllers\Web;

use App\Application\Services\CategoryService;
use App\Interfaces\Http\Controllers\Controller;
use App\Interfaces\Http\Requests\Category\CreateCategoryRequest;
use App\Interfaces\Http\Requests\Category\UpdateCategoryRequest;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService) {}

    public function index()
    {
        return view('categories.index');
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(CreateCategoryRequest $request)
    {
        $dto = $request->toDto();
        $this->categoryService->create($dto);

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(int $id)
    {
        $category = $this->categoryService->findById($id);

        return view('categories.edit', compact('category'));
    }

    public function update(int $id, UpdateCategoryRequest $request)
    {
        $dto = $request->toDto();
        $this->categoryService->update($dto, $id);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->categoryService->destroy($id);

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
