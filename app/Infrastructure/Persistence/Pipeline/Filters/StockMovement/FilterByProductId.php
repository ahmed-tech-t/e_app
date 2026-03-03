<?php

namespace App\Infrastructure\Persistence\Pipeline\Filters\StockMovement;

use Closure;

class FilterByProductId
{
    public function handle(StockMovementQueryContext $context, Closure $next)
    {
        $id = $context->dto->product_id;
        if ($id) {
            // We tell Eloquent to look inside the 'batch' relationship
            $context->query->whereHas('batch', function ($query) use ($id) {
                $query->where('product_id', $id);
            });
        }

        return $next($context);
    }
}