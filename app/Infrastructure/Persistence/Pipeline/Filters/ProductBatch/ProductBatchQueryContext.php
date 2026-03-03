<?php

namespace App\Infrastructure\Persistence\Pipeline\Filters\ProductBatch;

use App\Infrastructure\Persistence\Pipeline\Filters\QueryContext;
use Illuminate\Database\Eloquent\Builder;

class ProductBatchQueryContext implements QueryContext
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