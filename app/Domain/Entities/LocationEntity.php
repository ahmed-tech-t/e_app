<?php

namespace App\Domain\Entities;

use Carbon\Carbon;

class LocationEntity
{
    public function __construct(
        public string $name,
        public ?int $id = null,
        public ?string $code = null,
        public ?string $address = null,
        public ?string $phone = null,
        public ?string $type = null,
        public ?int $quantity = null,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null
    ) {}

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
        $this->name = $data['name'] ?? $this->name;
        $this->address = $data['address'] ?? $this->address;
        $this->phone = $data['phone'] ?? $this->phone;
        $this->type = $data['type'] ?? $this->type;

        return $this;
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
