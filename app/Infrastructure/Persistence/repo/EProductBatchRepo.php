<?php
namespace App\Infrastructure\Persistence\repo;

use App\Application\Mapper\ProductBatchMapper;
use App\Application\Mapper\ProductMapper;
use App\Domain\Entities\ProductBatchEntity;
use App\Domain\Repo\LocationRepo;
use App\Domain\Repo\ProductBatchRepo;
use App\Domain\Repo\StockMovementRepo;
use App\Infrastructure\Persistence\Models\Location;
use App\Infrastructure\Persistence\Models\ProductBatch;
use App\Infrastructure\Persistence\Pipeline\Filters\ProductBatch\FilterByCode;
use App\Infrastructure\Persistence\Pipeline\Filters\ProductBatch\ProductBatchQueryContext;
use App\Infrastructure\Persistence\utils\StockMovementType;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function Symfony\Component\String\s;

class EProductBatchRepo extends BaseERepo implements ProductBatchRepo
{
     protected $modelClass = ProductBatch::class;
     protected $mapper = ProductBatchMapper::class;
     public function __construct()
     {
     }


     public function search($code)
     {
          $context = ProductBatchQueryContext::create(ProductBatch::query(), $code);

          $context = app(Pipeline::class)
               ->send($context)
               ->through([
                    FilterByCode::class
               ])
               ->thenReturn();
          return $context->query
               ->with('locations')
               ->with('product')
               ->get()
               ->map(
                    fn($item) =>
                    ProductBatchMapper::modelToEntity($item)
               );
     }


     public function getBatchByProductIdFIFO($productId)
     {
          return ProductBatch::where('product_id', $productId)
               ->where('remaining_quantity', '>', 0)
               ->oldest()->first();
     }



     public function getProductQuantityInLocation($productId, $locationId)
     {
          return ProductBatch::where('product_id', $productId)
               ->where('location_id', $locationId)
               ->sum('remaining_quantity');
     }



     public function getProductBatchesInLocation($productId, $locationId)
     {
          return ProductBatch::where('product_id', $productId)
               ->where('location_id', $locationId)
               ->orderBy('created_at', 'asc') //FIFO
               ->lockForUpdate()
               ->get();
     }
}