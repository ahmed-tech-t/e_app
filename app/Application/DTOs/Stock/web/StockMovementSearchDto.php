<?php
namespace App\Application\DTOs\stock\web;

use App\Application\DTOs\Stock\StockSearchInterfaceDto;
use App\Infrastructure\Persistence\utils\StockMovementType;

class StockMovementSearchDto implements StockSearchInterfaceDto
{
    public function __construct(
        public ?string $search,
        public ?int $location_id,
        public ?StockMovementType $type,
        public ?string $dataFrom,
    ) {
    }
    public function toArray()
    {
        return [
            'search' => $this->search,
            'location_id' => $this->location_id,
            'type' => $this->type,
            'date_from' => $this->dataFrom
        ];
    }
}