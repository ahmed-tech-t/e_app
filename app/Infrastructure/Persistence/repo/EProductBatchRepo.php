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
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;


class EProductBatchRepo extends BaseERepo implements ProductBatchRepo
{
     protected $modelClass = ProductBatch::class;
     protected $mapper = ProductBatchMapper::class;
     public function __construct(private StockMovementRepo $stockMovementRepo)
     {
     }

     public function createBatchAndSetLocation(ProductBatchEntity $entity, int $locationId)
     {
          return DB::transaction(function () use ($entity, $locationId) {
               $model = ProductBatch::create($entity->toArray());
               $this->stockMovementRepo->entry(batchId: $model->id, locationId: $locationId, quantity: $entity->initialQuantity);
               $model->refresh();
               return ($this->mapper)::modelToEntity($model);

          });
     }

     public function update($entity)
     {
          return DB::transaction(function () use ($entity) {

               $model = ProductBatch::where('id', $entity->id)
                    ->lockForUpdate()
                    ->findOrFail($entity->id);

               if ($entity->remainingQuantity <= $model->initial_quantity) {
                    $model->update($entity->toArray());
               } else {
                    throw new \Exception("Cannot exceed initial quantity.");
               }

               return ($this->mapper)::modelToEntity($model->refresh());
          });

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
}