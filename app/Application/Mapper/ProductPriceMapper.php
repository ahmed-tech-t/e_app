<?php

namespace App\Application\Mapper;

use App\Domain\Entities\ProductPriceEntity;
use App\Infrastructure\Persistence\utils\PriceType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProductPriceMapper
{
    public static function modelToEntity($model)
    {
        return new ProductPriceEntity(
            id: $model['id'],
            productId: $model['product_id'],
            type: $model['type'],
            price: $model['price'],
            validFrom: Carbon::createFromTimeString($model['valid_from']),
            validTo: $model['valid_to'] ? Carbon::createFromTimeString($model['valid_to']) : null,
        );
    }
}