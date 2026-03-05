<?php

namespace App\Domain\Entities;

use Carbon\Carbon;


class SupplierEntity
{

    public function __construct(
        public string $name,
        public ?string $code = null,
        public ?int $id = null,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $address = null,
        public ?Carbon $created_at = null
    ) {

    }

    public static function create(array $data)
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            phone: $data['phone'],
            address: $data['address'],
        );
    }
    public function update(array $data)
    {
        $this->name = $data['name'] ?? $this->name;
        $this->email = $data['email'] ?? $this->email;
        $this->phone = $data['phone'] ?? $this->phone;
        $this->address = $data['address'] ?? $this->address;
        return $this;
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'code' => $this->code,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
        ];
    }
}