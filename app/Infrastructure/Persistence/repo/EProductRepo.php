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
        $dataModel = ProductMapper::entityToModel($data);
        $model = Product::create($dataModel)->refresh();
        return ProductMapper::modelToEntity($model);
    }

    public function update($data, $product)
    {
        return $product->update($data);
    }

    public function destory($product): string
    {
        $product->delete();
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