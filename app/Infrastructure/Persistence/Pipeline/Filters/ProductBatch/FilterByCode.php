<?php

namespace App\Infrastructure\Persistence\Pipeline\Filters\ProductBatch;


use Closure;


class FilterByCode
{
    public function handle(ProductBatchQueryContext $context, Closure $next)
    {
        if ($context->code) {
            $context->query->where(
                'batch_code',
                $context->code
            );
        }

        return $next($context);
    }
}