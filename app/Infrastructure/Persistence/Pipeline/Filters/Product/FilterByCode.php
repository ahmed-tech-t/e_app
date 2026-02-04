<?php

namespace App\Infrastructure\Persistence\Pipeline\Filters\Product;

use Closure;


class FilterByCode
{
    public function handle(ProductQueryContext $context, Closure $next)
    {
        if ($context->dto->code) {
            $context->query->where(
                'code',
                'LIKE',
                '%' . $context->dto->code . '%'

            );
        }

        return $next($context);
    }
}