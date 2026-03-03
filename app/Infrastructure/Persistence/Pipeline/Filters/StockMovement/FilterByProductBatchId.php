<?php

namespace App\Infrastructure\Persistence\Pipeline\Filters\StockMovement;

use Closure;

class FilterByProductBatchId
{
    public function handle(StockMovementQueryContext $context, Closure $next)
    {
        $id = $context->dto->product_batch_id;
        if ($id) {
            $context->query->where('product_batch_id', $id);
        }

        return $next($context);
    }
}