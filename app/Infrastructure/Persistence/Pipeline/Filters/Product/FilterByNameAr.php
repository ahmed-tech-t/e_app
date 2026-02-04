<?php

namespace App\Infrastructure\Persistence\Pipeline\Filters\Product;

use Closure;


class FilterByNameAr
{
    public function handle(ProductQueryContext $context, Closure $next)
    {
        if ($context->dto->name_ar) {
            $context->query->where(
                'name_ar',
                'LIKE',
                '%' . $context->dto->name_ar . '%'

            );
        }
        return $next($context);
    }
}