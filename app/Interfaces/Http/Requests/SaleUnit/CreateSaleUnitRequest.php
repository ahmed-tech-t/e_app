<?php

namespace App\Interfaces\Http\Requests\saleUnit;

use App\Application\DTOs\SaleUnitDto;
use Illuminate\Foundation\Http\FormRequest;

class CreateSaleUnitRequest extends FormRequest
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
            'name_ar' => 'required|string|max:255|unique:sale_units,name_ar',
            'name_en' => 'nullable|string|max:255|unique:sale_units,name_en',
        ];
    }

    public function toDto()
    {
        return new SaleUnitDto($this->name_ar, $this->name_en ?? null);
    }
}
