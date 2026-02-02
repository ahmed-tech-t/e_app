<?php

namespace App\Application\Mapper;

use App\Application\DTOs\CreateProductDto;
use App\Domain\Entities\ProductEntity;

class ProductMapper implements Mapper
{
    public static function createProductdtoToEntity(CreateProductDto $dto): ProductEntity
    {
        return new ProductEntity(
            original_code: $dto->original_code,
            categoryId: $dto->categoryId,
            name_ar: $dto->name_ar,
            name_en: $dto->name_en,
            description: $dto->description,
            brand: $dto->brand,
            image: $dto->image,
            origin: $dto->origin,
            unitsPerCarton: $dto->unitsPerCarton,
            saleUnitId: $dto->saleUnitId
        );
    }

    public static function entityToModel($entity)
    {
        $data = [
            'code' => $entity->code,
            'original_code' => $entity->original_code,
            'name_ar' => $entity->name_ar,
            'name_en' => $entity->name_en,
            'category_id' => $entity->categoryId,
            'description' => $entity->description,
            'brand' => $entity->brand,
            'units_per_carton' => $entity->unitsPerCarton,
            'sale_unit_id' => $entity->saleUnitId
        ];

        if ($entity->image)
            $data['image'] = $entity->image;

        if ($entity->origin)
            $data['origin'] = $entity->origin;

        return $data;
    }

    public static function modelToEntity($model)
    {
        return new ProductEntity(
            id: $model->id,
            code: $model->code,
            original_code: $model->original_code,
            name_ar: $model->name_ar,
            name_en: $model->name_en,
            categoryId: $model->category_id,


            category: $model->relationLoaded('category')
            ? CategoryMapper::modelToEntity($model->category) : null,

            description: $model->description,
            brand: $model->brand,
            image: $model->image,
            origin: $model->origin,
            unitsPerCarton: $model->units_per_carton,
            saleUnitId: $model->sale_unit_id,
            saleUnit: $model->relationLoaded('saleUnit')
            ? SaleUnitMapper::modelToEntity($model->saleUnit) : null,
            created_at: $model->created_at,
            updated_at: $model->updated_at
        );
    }

    /**
     * @inheritDoc
     */
    public static function dtoToEntity($dto)
    {
    }
}