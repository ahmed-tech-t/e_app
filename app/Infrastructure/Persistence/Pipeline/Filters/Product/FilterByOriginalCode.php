<?php
namespace App\Infrastructure\Persistence\Pipeline\Filters\Product;

use Closure;


class FilterByOriginalCode
{
    public function handle(ProductQueryContext $context, Closure $next)
    {
        if ($context->dto->original_code) {
            $context->query->where(
                'original_code',
                'LIKE',
                '%' . $context->dto->original_code . '%'

            );
        }
        return $next($context);
    }
}