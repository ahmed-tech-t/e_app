<?php
namespace App\Infrastructure\Persistence\repo;

use App\Application\Mapper\ProductBatchMapper;
use App\Application\Mapper\ProductMapper;
use App\Domain\Entities\ProductBatchEntity;
use App\Domain\Repo\LocationRepo;
use App\Domain\Repo\ProductBatchRepo;
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

     public function createBatchAndSetLocation(ProductBatchEntity $entity, int $locationId)
     {
          $model = ProductBatch::create($entity->toArray())->refresh();
          $model->locations()->attach(
               $locationId,
               ['remaining_quantity' => $entity->initialQuantity]
          );
          return ($this->mapper)::modelToEntity($model);
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

     public function transfer($batchId, $fromLocationId, $toLocationId, $quantity)
     {
          DB::transaction(function () use ($batchId, $fromLocationId, $toLocationId, $quantity) {
               // 1. Find the Batch first
               $batch = ProductBatch::findOrFail($batchId);
               $source = $batch->locations()->where('location_id', $fromLocationId)->firstOrFail();
               $currentQty = $source->pivot->remaining_quantity;

               if ($currentQty < $quantity) {
                    throw new \Exception("Insufficient stock in source location.");
               }

               $batch->locations()->updateExistingPivot($fromLocationId, [
                    'remaining_quantity' => $currentQty - $quantity
               ]);


               $destination = $batch->locations()->where('location_id', $toLocationId)->firstOrFail();

               if ($destination) {
                    $batch->locations()->updateExistingPivot($toLocationId, [
                         'remaining_quantity' => $destination->pivot->remaining_quantity + $quantity
                    ]);
               } else {
                    $batch->locations()->attach($toLocationId, [
                         'remaining_quantity' => $quantity
                    ]);
               }
          });
     }
}