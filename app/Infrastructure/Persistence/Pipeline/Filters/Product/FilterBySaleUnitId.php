<?php

namespace App\Infrastructure\Persistence\Pipeline\Filters\Product;

use Closure;


class FilterBySaleUnitId
{
    public function handle(ProductQueryContext $context, Closure $next)
    {
        if ($context->dto->saleUnitId) {
            $context->query->where(
                'sale_unit_id',
                'LIKE',
                '%' . $context->dto->saleUnitId . '%'

            );
        }
        return $next($context);
    }
}