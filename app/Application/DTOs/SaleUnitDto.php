<?php

namespace App\Application\DTOs;

class SaleUnitDto
{

    public function __construct(
        public string $name_ar,
        public string $name_en,
    ) {
    }

    public function toArray()
    {
        return [
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en
        ];
    }
}