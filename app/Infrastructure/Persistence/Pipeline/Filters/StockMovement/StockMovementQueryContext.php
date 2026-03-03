<?php

namespace App\Infrastructure\Persistence\Pipeline\Filters\StockMovement;

use App\Application\DTOs\StockMovementSearchDto;
use App\Infrastructure\Persistence\Pipeline\Filters\QueryContext;
use Illuminate\Database\Eloquent\Builder;

class StockMovementQueryContext implements QueryContext
{
    public function __construct(
        public Builder $query,
        public StockMovementSearchDto $dto
    ) {
    }

    public static function create(
        Builder $query,
        StockMovementSearchDto $dto
    ) {
        return new self(
            query: $query,
            dto: $dto
        );
    }
}