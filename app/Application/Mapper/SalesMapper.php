<?php

namespace App\Application\Mapper;

use App\Domain\Entities\SalesEntity;

class SalesMapper
{
    public static function modelToEntity($model)
    {
        return new SalesEntity(
            id: $model['id'],
            customer_name: $model['customer_name'],
            total: $model['total'],
            discount: $model['discount'],
            tax: $model['tax'],
            grand_total: $model['grand_total'],
            //items: $model['items'],
        );
    }
}