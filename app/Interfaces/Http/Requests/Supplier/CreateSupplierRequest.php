<?php

namespace App\Interfaces\Http\Requests\Supplier;

use App\Application\DTOs\SupplierDto;
use App\Utils\ValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class CreateSupplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ValidationRules::name(),
            'phone' => ValidationRules::phone(false),
            'address' => ValidationRules::name(false),
            'email' => ValidationRules::email(false),
        ];
    }

    public function toDto()
    {
        $data = $this->validated();
        return new SupplierDto(
            name: $data['name'],
            phone: $data['phone'] ?? null,
            address: $data['address'] ?? null,
            email: $data['email'] ?? null
        );
    }
}
