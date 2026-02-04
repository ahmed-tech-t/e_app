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

    public function toArray()
    {
        return [
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'code' => $this->code,
        ];
    }
}