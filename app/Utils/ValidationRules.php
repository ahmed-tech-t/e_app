<?php


namespace App\Utils;
class ValidationRules
{
    public static function price(): string
    {
        return 'required|numeric|regex:/^\d+(\.\d{1,2})?$/';
    }

    public static function productId(): string
    {
        return 'required|integer|exists:products,id';
    }

    public static function locationId(): string
    {
        return 'required|integer|exists:locations,id';
    }

    public static function quantity(): string
    {
        return 'required|integer|min:1';
    }
}