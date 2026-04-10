<?php

namespace App\Infrastructure\Persistence\Pipeline\Filters\StockMovement;

use App\Application\DTOs\Stock\StockSearchInterfaceDto;
use App\Infrastructure\Persistence\Pipeline\Filters\QueryContext;
use Illuminate\Database\Eloquent\Builder;

class StockMovementQueryContext implements QueryContext
{
    public function __construct(
        public Builder $query,
        public StockSearchInterfaceDto $dto
    ) {
    }

    public static function create(
        Builder $query,
        StockSearchInterfaceDto $dto
    ) {
        return new self(
            query: $query,
            dto: $dto
        );
    }
}