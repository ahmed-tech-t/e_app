<?php

namespace App\Application\DTOs;

class SaleUnitDto
{

    public function __construct(
        public string $name,
        public ?int $id = null,
        public ?string $code = null
    ) {
    }

}