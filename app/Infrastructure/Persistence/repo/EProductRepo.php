<?php

namespace App\Infrastructure\Persistence\repo;

use App\Application\Mapper\ProductMapper;
use App\Domain\Entities\ProductEntity;
use App\Domain\Repo\Product\ProductRepo;
use App\Infrastructure\Persistence\Models\Category;
use App\Infrastructure\Persistence\Models\Product;
use App\Traits\CodeGenerator;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EProductRepo implements ProductRepo
{
    use CodeGenerator;

    public function getPaginatedProducts($perPage = 5): LengthAwarePaginator
    {
        $products = Product::paginate($perPage)
            ->through(
                fn($item) =>
                ProductMapper::modelToEntity($item)
            );
        return $products;
    }

    public function findById(int $id): ProductEntity
    {
        $model = Product::with('category', 'saleUnit')->findOrFail($id);
        return ProductMapper::modelToEntity($model);
    }

    public function create(ProductEntity $data): ProductEntity
    {

        $model = Product::create($data->toArray())->refresh();
        return ProductMapper::modelToEntity($model);
    }

    public function update(ProductEntity $entity): ProductEntity
    {

        Product::where('id', $entity->id)->update($entity->toArray());
        return $entity;
    }

    public function destory(int $id): string
    {
        Product::destroy($id);
        return "Product deleted successfully";
    }

    /**
     * @inheritDoc
     */
    public function codeExists(string $code): bool
    {
        return Product::where('code', $code)->exists();
    }
}