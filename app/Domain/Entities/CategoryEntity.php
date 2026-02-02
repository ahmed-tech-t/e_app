<?php

namespace App\Domain\Entities;

class CategoryEntity
{
    public function __construct(
        public string $name_ar,
        public string $name_en,
        public ?int $id = null,
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