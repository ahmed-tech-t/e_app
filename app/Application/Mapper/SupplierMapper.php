<?php

namespace App\Application\Mapper;

use App\Domain\Entities\SupplierEntity;

class SupplierMapper
{
    public static function modelToEntity($model)
    {
        return new SupplierEntity(
            id: $model['id'],
            code: $model['code'],
            name: $model['name'],
            email: $model['email'],
            phone: $model['phone'],
            address: $model['address'],
        );
    }
}