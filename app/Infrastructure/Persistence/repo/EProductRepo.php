<?php

namespace App\Infrastructure\Persistence\repo;

use App\Application\DTOs\ProductSearchDto;
use App\Application\Mapper\ProductMapper;
use App\Domain\Entities\ProductEntity;
use App\Domain\Entities\ProductPriceEntity;
use App\Domain\Repo\ProductRepo;
use App\Infrastructure\Persistence\Models\ProductPrice;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EProductRepo extends BaseERepo implements ProductRepo
{

    protected $modelClass = Product::class;
    protected $mapper = ProductMapper::class;
    protected array $defaultRelationships = ['category', 'saleUnit'];

    protected array $withForPaginate = ['retailPrice', 'wholesalePrice'];
    public function search(ProductSearchDto $dto, $perPage = 5): LengthAwarePaginator
    {
        $context = ProductQueryContext::create(Product::query(), $dto);

        $context = app(Pipeline::class)
            ->send($context)
            ->through([
                FilterByCode::class,
                FilterByBrand::class,
                FilterByCategoryId::class,
                FilterByNameEn::class,
                FilterByNameAr::class,
                FilterByOriginalCode::class,
                FilterBySaleUnitId::class,
            ])

            ->thenReturn();

        return $context->query
            ->paginate($perPage)
            ->through(
                fn($item) =>
                ProductMapper::modelToEntity($item)
            );
    }
}