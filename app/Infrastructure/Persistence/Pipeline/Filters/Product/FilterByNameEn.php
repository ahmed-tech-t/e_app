<?php

namespace App\Infrastructure\Persistence\Pipeline\Filters\Product;

use Closure;


class FilterByNameEn
{
    public function handle(ProductQueryContext $context, Closure $next)
    {

        if ($context->dto->name_en) {
            $context->query->where(
                'name_en',
                'LIKE',
                '%' . $context->dto->name_en . '%'

            );
        }
        return $next($context);
    }
}