<?php

namespace App\Application\Mapper;

use App\Domain\Entities\SaleUnitEntity;

class SaleUnitMapper
{
    public static function modelToEntity($model)
    {
        return new SaleUnitEntity(
            id: $model['id'],
            name_ar: $model['name_ar'],
            name_en: $model['name_en'],
            code: $model['code'],

        );
    }
}