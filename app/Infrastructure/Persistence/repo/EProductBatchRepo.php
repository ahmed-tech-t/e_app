<?php
namespace App\Infrastructure\Persistence\repo;

use App\Application\Mapper\ProductBatchMapper;

use App\Domain\Repo\ProductBatchRepo;

use App\Infrastructure\Persistence\Models\ProductBatch;
use App\Infrastructure\Persistence\Pipeline\Filters\ProductBatch\FilterByCode;
use App\Infrastructure\Persistence\Pipeline\Filters\ProductBatch\ProductBatchQueryContext;


use function Symfony\Component\String\s;

class EProductBatchRepo extends BaseERepo implements ProductBatchRepo
{
     protected $modelClass = ProductBatch::class;
     protected $mapper = ProductBatchMapper::class;

     protected $queryContext = ProductBatchQueryContext::class;

     protected array $withSearch = ['locations', 'product'];
     protected array $searchFilters = [FilterByCode::class];

     public function __construct()
     {
     }

     public function getBatchByProductIdFIFO($productId)
     {
          return ProductBatch::where('product_id', $productId)
               ->where('remaining_quantity', '>', 0)
               ->oldest()->first();
     }



     public function getProductQuantityInLocation($productId, $locationId)
     {
          return ProductBatch::withLocationStock($locationId, $productId)
               ->sum('batch_locations.remaining_quantity');
     }



     public function getProductBatchesInLocation($productId, $locationId)
     {
          return ProductBatch::withLocationStock($locationId, $productId)
               ->orderBy('product_batches.created_at', 'asc') //FIFO
               ->lockForUpdate()
               ->get();
     }

     public function getBatchesInLocation($productId, $locationId)
     {
          return ProductBatch::withLocationStock($locationId, $productId)
               ->orderBy('product_batches.created_at', 'asc')
               ->select([
                    'product_batches.id as batch_id',
                    'batch_locations.location_id as location_id',
                    'batch_locations.remaining_quantity as quantity',
               ])
               ->lockForUpdate()
               ->get();
     }
}