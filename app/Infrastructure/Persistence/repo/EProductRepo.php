<?php

namespace App\Infrastructure\Persistence\repo;

use App\Application\Mapper\ProductMapper;

use App\Domain\Repo\ProductRepo;
use App\Infrastructure\Persistence\Pipeline\Filters\Product\FilterByBrand;
use App\Infrastructure\Persistence\Pipeline\Filters\Product\FilterByCategoryId;
use App\Infrastructure\Persistence\Pipeline\Filters\Product\FilterByCode;
use App\Infrastructure\Persistence\Pipeline\Filters\Product\FilterByNameAr;
use App\Infrastructure\Persistence\Pipeline\Filters\Product\FilterByNameEn;
use App\Infrastructure\Persistence\Pipeline\Filters\Product\FilterByOriginalCode;
use App\Infrastructure\Persistence\Pipeline\Filters\Product\FilterBySaleUnitId;
use App\Infrastructure\Persistence\Models\Product;
use App\Infrastructure\Persistence\Pipeline\Filters\Product\ProductQueryContext;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pipeline\Pipeline;

class EProductRepo extends BaseERepo implements ProductRepo
{

    protected $modelClass = Product::class;
    protected $mapper = ProductMapper::class;

    protected $queryContext = ProductQueryContext::class;

    protected array $searchFilters = [
        FilterByCode::class,
        FilterByBrand::class,
        FilterByCategoryId::class,
        FilterByNameEn::class,
        FilterByNameAr::class,
        FilterByOriginalCode::class,
        FilterBySaleUnitId::class,
    ];


    protected array $defaultRelationships = ['category', 'saleUnit'];

    public function __construct()
    {

    }


    public function findById(int $id)
    {
        $item = Product::allProducts()
            ->where('products.id', $id)
            ->first();

        return $item ? ProductMapper::modelToEntity($item) : null;
    }


    public function getPaginatedItems($perPage): LengthAwarePaginator
    {
        return Product::
            allProducts()
            ->orderBy('products.created_at', 'desc')
            ->paginate($perPage)
            ->through(fn($item) => ProductMapper::modelToEntity($item));
    }


    public function search($dto, $perPage)
    {
        $query = $dto->locationId
            ? Product::atLocation($dto->locationId, true)
            : Product::allProducts();

        $context = ($this->queryContext)::create($query, $dto);
        $result = app(Pipeline::class)
            ->send($context)
            ->through($this->searchFilters)
            ->thenReturn()
            ->query
            ->paginate($perPage);

        if ($result->isEmpty() && isset($dto->code)) {
            $this->handleEmptySearchResult($dto->code);
        }
        return $result->through(
            fn($item) => ($this->mapper)::modelToEntity($item)
        );
    }
    private function handleEmptySearchResult($code)
    {

    }
    public function findAllByLocation(int $locationId, int $perPage)
    {
        return Product::
            atLocation($locationId)
            ->paginate($perPage)
            ->through(fn($item) => ProductMapper::modelToEntity($item));
    }
    public function findByLocation(int $productId, int $locationId)
    {
        $product = Product::
            atLocation($locationId)
            ->where('products.id', $productId)
            ->with($this->defaultRelationships)
            ->first();
        return $product ? ProductMapper::modelToEntity($product) :
            throw new \Exception('Out of stock');
    }
}