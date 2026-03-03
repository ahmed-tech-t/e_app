<?php

namespace App\Infrastructure\Persistence\Pipeline\Filters\Product;

use Closure;

class FilterByLocation
{
    public function handle(ProductQueryContext $context, Closure $next)
    {

        $locationId = $context->dto->locationId;
        if ($locationId) {
            $context->query->withLocationStock($locationId);

        }
        return $next($context);
    }
}