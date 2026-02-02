<?php
namespace App\Domain\Entities;

class SaleUnitEntity
{
    public function __construct(
        public string $name_ar,
        public ?int $id = null,
        public ?string $name_en = null,
        public ?string $code = null
    ) {
    }
}