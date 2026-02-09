<?php

namespace App\Application\Mapper;

use App\Domain\Entities\CategoryEntity;

class CategoryMapper
{
    public static function modelToEntity($model)
    {
        return new CategoryEntity(
            id: $model['id'],
            name_ar: $model['name_ar'],
            name_en: $model['name_en'],
            code: $model['code']
        );
    }
}