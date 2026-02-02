<?php

namespace App\Application\Mapper;
interface Mapper
{

    public static function modelToEntity($model);

    public static function dtoToEntity($dto);
}