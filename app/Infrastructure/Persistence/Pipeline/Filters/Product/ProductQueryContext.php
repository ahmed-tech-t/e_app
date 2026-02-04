<?php

namespace App\Infrastructure\Persistence\Pipeline\Filters\Product;

use App\Application\DTOs\ProductSearchDto;
use Illuminate\Database\Eloquent\Builder;

class ProductQueryContext
{
    private function __construct(
        public Builder $query,
        public ProductSearchDto $dto
    ) {
    }

    public static function create(
        Builder $query,
        ProductSearchDto $dto
    ) {
        return new self(
            query: $query,
            dto: $dto
        );
    }
}