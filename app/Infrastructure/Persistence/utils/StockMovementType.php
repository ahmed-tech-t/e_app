<?php
namespace App\Infrastructure\Persistence\utils;

enum StockMovementType: string
{
    case SALE = 'sale';
    case ENTRY = 'entry';
    case TRANSFER_IN = 'transfer_in';
    case TRANSFER_OUT = 'transfer_out';
    case ADJUSTMENT = 'adjustment';
}