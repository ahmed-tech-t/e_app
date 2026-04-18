<?php

namespace App\Domain\Utils;
enum ReturnedItemStatus: string
{
    case DAMAGED = 'damaged';
    case RESEALABLE = 'resealable';
}