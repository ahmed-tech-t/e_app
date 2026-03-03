<?php

namespace App\Infrastructure\Persistence\Pipeline\Filters\StockMovement;

use Closure;

class FilterByLocationId
{
    public function handle(StockMovementQueryContext $context, Closure $next)
    {
        $id = $context->dto->location_id;
        if ($id) {
            $context->query->where('location_id', $id);
        }

        return $next($context);
    }
}