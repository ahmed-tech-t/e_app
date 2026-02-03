<?php

namespace App\Application\DTOs;

class CategoryDto
{
    public function __construct(
        public ?string $name_ar = null,
        public ?string $name_en = null,
    ) {
    }

    public function toArray()
    {
        return [
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
        ];
    }
}