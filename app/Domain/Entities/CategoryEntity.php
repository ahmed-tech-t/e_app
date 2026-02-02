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
}