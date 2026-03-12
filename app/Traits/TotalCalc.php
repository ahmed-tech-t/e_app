<?php

namespace App\Traits;

use App\Infrastructure\Persistence\utils\Constants;

trait TotalCalc
{
    public static function getTotal(array $items)
    {
        $total = 0;
        foreach ($items as $item) {
            $total += $item->total;
        }
        return round($total, 2);
    }

    public static function getGrandTotal(float $total, float $discount, float $taxValue = Constants::TAX)
    {
        $discount = $discount * $total;

        $tax = ($taxValue / 100) * $total;
        return round($total - ($discount / 100) + $tax, 2);
    }
}