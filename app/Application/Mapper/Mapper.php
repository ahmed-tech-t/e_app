<?php

namespace App\Application\Mapper;
interface Mapper
{
    public static function entityToModel($entity);
    public static function modelToEntity($model);

    public static function dtoToEntity($dto);
}