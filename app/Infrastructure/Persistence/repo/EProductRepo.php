<?php

namespace App\Infrastructure\Persistence\repo;

use App\Application\Mapper\ProductMapper;

use App\Domain\Repo\ProductRepo;
use App\Infrastructure\Persistence\Pipeline\Filters\Product\FilterByBrand;
use App\Infrastructure\Persistence\Pipeline\Filters\Product\FilterByCategoryId;
use App\Infrastructure\Persistence\Pipeline\Filters\Product\FilterByCode;
use App\Infrastructure\Persistence\Pipeline\Filters\Product\FilterByLocation;
use App\Infrastructure\Persistence\Pipeline\Filters\Product\FilterByNameAr;
use App\Infrastructure\Persistence\Pipeline\Filters\Product\FilterByNameEn;
use App\Infrastructure\Persistence\Pipeline\Filters\Product\FilterByOriginalCode;
use App\Infrastructure\Persistence\Pipeline\Filters\Product\FilterBySaleUnitId;
use App\Infrastructure\Persistence\Models\Product;
use App\Infrastructure\Persistence\Pipeline\Filters\Product\ProductQueryContext;
use App\Infrastructure\Persistence\utils\Constants;


class EProductRepo extends BaseERepo implements ProductRepo
{

    protected $modelClass = Product::class;
    protected $mapper = ProductMapper::class;

    protected $queryContext = ProductQueryContext::class;

    protected array $searchFilters = [
        FilterByLocation::class,
        FilterByCode::class,
        FilterByBrand::class,
        FilterByCategoryId::class,
        FilterByNameEn::class,
        FilterByNameAr::class,
        FilterByOriginalCode::class,
        FilterBySaleUnitId::class,
    ];

    //protected array $withForPaginate = ['retailPrice', 'wholesalePrice'];
    protected array $withSearch = [];
    protected array $defaultRelationships = ['category', 'saleUnit'];

    public function __construct()
    {
        $this->withSearch = $this->withForPaginate;
    }
    public function findAllByLocation(int $locationId, int $perPage)
    {
        return Product::withLocationStock($locationId)
            ->paginate($perPage)
            ->through(fn($item) => ProductMapper::modelToEntity($item));
    }
    public function findByLocation(int $productId, int $locationId)
    {
        $product = Product::with($this->defaultRelationships)
            ->withLocationStock($locationId)
            ->where('products.id', $productId)
            ->first();
        return $product ? ProductMapper::modelToEntity($product) : null;

    }
}