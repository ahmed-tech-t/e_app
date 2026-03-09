<?php
namespace App\Infrastructure\Persistence\repo;

use App\Application\Mapper\ProductPriceMapper;
use App\Domain\Entities\ProductPriceEntity;
use App\Domain\Repo\ProductPriceRepo;
use App\Infrastructure\Persistence\Models\ProductPrice;
use App\Infrastructure\Persistence\utils\PriceType;


class EProductPriceRepo extends BaseERepo implements ProductPriceRepo
{
     protected $modelClass = ProductPrice::class;
     protected $mapper = ProductPriceMapper::class;
     protected array $withForPaginate = ['product'];

     public function createMany(array $entities): bool
     {
          foreach ($entities as $entity) {
               $this->create($entity);
          }
          return true;
     }



     public function invalidateOldPrice(ProductPrice $productPrice): bool
     {
          $oldProductPrice = ProductPrice::where('product_id', $productPrice->product_id)
               ->where('type', $productPrice->type)
               ->whereNull('valid_to')
               ->first();

          // 1. لا يوجد سعر قديم
          if (!$oldProductPrice) {
               return true;
          }


          if (number_format((float) $oldProductPrice->price, 2) === number_format((float) $productPrice->price, 2)) {
               return false;
          }

          return $oldProductPrice->update(['valid_to' => now()]);
     }



     /**
      * @inheritDoc
      */
     public function getProductPriceHistory(int $productId, ?PriceType $type)
     {
          $query = ProductPrice::where('product_id', $productId);

          if ($type) {
               $query->where('type', $type);
          }
          return $query
               ->get()
               ->map(fn($model) => ProductPriceMapper::modelToEntity($model));
     }

     /**
      * @inheritDoc
      */
     public function getByProductIdAndType(int $productId, PriceType $type)
     {
          $model = ProductPrice::where('product_id', $productId)
               ->where('type', $type)
               ->whereNull('valid_to')
               ->first();
          return ProductPriceMapper::modelToEntity($model);
     }
}