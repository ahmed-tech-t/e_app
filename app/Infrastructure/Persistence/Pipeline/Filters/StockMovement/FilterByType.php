<?php

namespace App\Infrastructure\Persistence\Pipeline\Filters\StockMovement;

use Closure;

class FilterByType
{
    public function handle(StockMovementQueryContext $context, Closure $next)
    {
        $type = $context->dto->type;
        if ($type) {
            $context->query->where('type', $type);
        }

        return $next($context);
    }
}