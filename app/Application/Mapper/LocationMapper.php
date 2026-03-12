<?php

namespace App\Application\Mapper;

use App\Domain\Entities\LocationEntity;

class LocationMapper
{
    public static function modelToEntity($model)
    {
        return new LocationEntity(
            id: $model['id'],
            name: $model['name'],
            address: $model['address'],
            phone: $model['phone'],
            type: $model['type'],
            code: $model['code'],
            created_at: $model['created_at'],
            updated_at: $model['updated_at']
        );
    }
}