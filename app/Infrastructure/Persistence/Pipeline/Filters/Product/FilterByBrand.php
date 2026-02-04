<?php

namespace App\Infrastructure\Persistence\Pipeline\Filters\Product;

use Closure;


class FilterByBrand
{
    public function handle(ProductQueryContext $context, Closure $next)
    {
        if ($context->dto->brand) {
            $context->query->where(
                'brand',
                'LIKE',
                '%' . $context->dto->brand . '%'

            );
        }
        return $next($context);
    }
}