<?php

namespace App\Infrastructure\Persistence\Pipeline\Filters\StockMovement;

use Closure;

class FilterByBillNumber
{
    public function handle(StockMovementQueryContext $context, Closure $next)
    {
        $billNumber = $context->dto->bill_number;
        if ($billNumber) {
            $context->query->where('bill_number', $billNumber);
        }

        return $next($context);
    }
}