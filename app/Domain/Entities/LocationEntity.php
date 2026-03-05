<?php

namespace App\Domain\Entities;

use Carbon\Carbon;


class LocationEntity
{

    public function __construct(
        public ?int $id = null,
        public ?string $code = null,
        public string $name,
        public ?string $address = null,
        public ?string $phone = null,
        public ?string $type = null,
        public ?int $quantity = null,

        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null
    ) {
    }

    public static function create(array $data)
    {
        return new self(
            name: $data['name'],
            address: $data['address'],
            phone: $data['phone'],
            type: $data['type']
        );
    }
    public function update(array $data)
    {
        return [
            'name' => $data['name'] ?? $this->name,
            'address' => $data['address'] ?? $this->address,
            'phone' => $data['phone'] ?? $this->phone,
            'type' => $data['type'] ?? $this->type,
        ];
    }

    public function toArray()
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone,
            'type' => $this->type,
        ];
    }
}