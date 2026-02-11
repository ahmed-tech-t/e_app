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
               $this->stockMovementRepo->adjust(batchId: $model->id, locationId: $locationId, quantity: $entity->initialQuantity, type: StockMovementType::ENTRY);
               $model->refresh();
               return ($this->mapper)::modelToEntity($model);

          });
     }

     public function update($entity, ?int $initialQuantity = null)
     {
          return DB::transaction(function () use ($entity, $initialQuantity) {

               $model = ProductBatch::
                    lockForUpdate()
                    ->findOrFail($entity->id);

               if (
                    $initialQuantity
                    //   && $model->initialQuantity != $initialQuantity
                    && $model->remaining_quantity == $model->initial_quantity
               ) {
                    if (!$this->stockMovementRepo->isTransferOut($model->id)) {
                         $diff = (float) $initialQuantity - (float) $model->initial_quantity;
                         $locationId = $model->locations()->first()->id;
                         $this->stockMovementRepo->adjust(
                              batchId: $entity->id,
                              locationId: $locationId,
                              quantity: $diff,
                              type: StockMovementType::ADJUST_INITIAL
                         );
                    } else
                         throw new \Exception("Cannot update initial quantity after stock movement");
               }

               $model->update($entity->toArray());
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