<?php

namespace App\Application\Mapper;

use App\Domain\Entities\SaleUnitEntity;

class SaleUnitMapper implements Mapper
{

    /**
     * @inheritDoc
     */
    public static function dtoToEntity($dto)
    {
        return new SaleUnitEntity(
            name_ar: $dto->name_ar,
            name_en: $dto->name_en
        );
    }

    /**
     * @inheritDoc
     */
    public static function entityToModel($entity)
    {
        return [
            'name_ar' => $entity->name_ar,
            'name_en' => $entity->name_en,
            'code' => $entity->code
        ];
    }

    /**
     * @inheritDoc
     */
    public static function modelToEntity($model)
    {
        return new SaleUnitEntity(
            id: $model['id'],
            name_ar: $model['name_ar'],
            name_en: $model['name_en'],
            code: $model['code']
        );
    }
}