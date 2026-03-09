<?php


namespace App\Utils;
class ValidationRules
{

    public static function percentage(bool $required = true): string
    {
        return self::base(
            validation: 'numeric|min:0|max:100|regex:/^\d+(\.\d{1,2})?$/',
            required: $required
        );
    }

    public static function array(bool $required = true): string
    {
        return self::base(
            validation: 'array|min:1',
            required: $required
        );
    }
    public static function code(bool $required = true): string
    {
        return self::base(
            validation: 'string|max:8',
            required: $required
        );
    }

    public static function price(bool $required = true): string
    {
        return self::base(
            validation: 'numeric|regex:/^\d+(\.\d{1,2})?$/',
            required: $required
        );
    }

    public static function productId(bool $required = true): string
    {
        return self::base(
            validation: 'integer|exists:products,id',
            required: $required
        );
    }


    public static function productBatchId(bool $required = true): string
    {
        return self::base(
            validation: 'integer|exists:product_batches,id',
            required: $required
        );
    }

    public static function categoryId(bool $required = true): string
    {
        return self::base(
            validation: 'integer|exists:categories,id',
            required: $required
        );
    }

    public static function saleUnitId(bool $required = true): string
    {
        return self::base(
            validation: 'integer|exists:sale_units,id',
            required: $required
        );
    }

    public static function name(bool $required = true): string
    {
        return self::base(
            validation: 'string|max:255',
            required: $required
        );
    }

    public static function locationId(bool $required = true): string
    {
        return self::base(
            validation: 'integer|exists:locations,id',
            required: $required
        );
    }

    public static function quantity(bool $required = true): string
    {
        return self::base(
            validation: 'integer|min:1',
            required: $required
        );
    }

    public static function image(bool $required = true): string
    {
        return self::base(
            validation: 'image|mimes:jpeg,png,jpg,svg|max:2048',
            required: $required
        );
    }

    private static function base(bool $required, $validation): string
    {
        if ($required) {
            return 'required|' . $validation;
        }
        return 'sometimes|' . $validation;
    }
}