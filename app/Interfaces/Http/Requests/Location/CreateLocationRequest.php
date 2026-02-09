<?php

namespace App\Interfaces\Http\Requests\Location;

use App\Application\DTOs\LocationDto;
use Illuminate\Foundation\Http\FormRequest;

class CreateLocationRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:255', 'in:store,warehouse'],
        ];
    }

    public function toDto()
    {
        $data = $this->validated();
        return new LocationDto(
            name: $data->name,
            address: $data->address ?? null,
            phone: $data->phone ?? null,
            type: $data->type ?? null,
        );
    }
}
