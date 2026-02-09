<?php

namespace App\Application\DTOs;



class LocationDto
{

    public function __construct(
        public string $name,
        public ?string $address = null,
        public ?string $phone = null,
        public ?string $type = null
    ) {

    }


    public function toArray()
    {
        return [
            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone,
            'type' => $this->type,
        ];
    }
}