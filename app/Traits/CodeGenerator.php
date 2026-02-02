<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait CodeGenerator
{
    public static function generateCode(string $name)
    {
        return strtoupper(substr($name, 0, 3))
            . '-'
            . str_pad(
                random_int(0, 9999),
                4,
                '0',
                STR_PAD_LEFT
            );
    }
}