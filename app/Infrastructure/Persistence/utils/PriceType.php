<?php

namespace App\Infrastructure\Persistence\utils;

enum PriceType: string
{
    case RETAIL = 'retail';
    case WHOLESALE = 'wholesale';
}