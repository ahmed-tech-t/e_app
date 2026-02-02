<?php

namespace App\Application\Mapper;

use App\Domain\Entities\CategoryEntity;

class CategoryMapper implements Mapper
{

    public static function entityToModel($entity)
    {
        return [
            'id' => $entity->id,
            'name_ar' => $entity->name_ar,
            'name_en' => $entity->name_en,
            'code' => $entity->code,
        ];
    }

    public static function modelToEntity($model)
    {
        return new CategoryEntity(
            id: $model['id'],
            name_ar: $model['name_ar'],
            name_en: $model['name_en'],
            code: $model['code']
        );
    }

    /**
     * @inheritDoc
     */
    public static function dtoToEntity($dto)
    {
        return new CategoryEntity(
            name_ar: $dto->name_ar,
            name_en: $dto->name_en,
            code: $dto->code
        );
    }


}