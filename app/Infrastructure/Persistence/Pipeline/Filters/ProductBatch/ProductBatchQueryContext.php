<?php

namespace App\Infrastructure\Persistence\Pipeline\Filters\ProductBatch;

use Illuminate\Database\Eloquent\Builder;

class ProductBatchQueryContext
{
    private function __construct(
        public Builder $query,
        public string $code
    ) {
    }

    public static function create(
        Builder $query,
        string $code
    ) {
        return new self(
            query: $query,
            code: $code
        );
    }
}