<?php

namespace App\Application\Mapper;

use App\Domain\Entities\ProductPriceEntity;
use Carbon\Carbon;

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
