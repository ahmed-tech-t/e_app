<?php


namespace App\Infrastructure\Persistence\Pipeline\Filters\Product;

use Closure;


class FilterByCategoryId
{
    public function handle(ProductQueryContext $context, Closure $next)
    {
        if ($context->dto->categoryId) {
            $context->query->where(
                'category_id',
                'LIKE',
                '%' . $context->dto->categoryId . '%'

            );
        }
        return $next($context);
    }
}