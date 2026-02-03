<?php

namespace App\Interfaces\Http\Requests\Product;

use App\Application\DTOs\CreateProductDto;
use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
            'category_id' => 'required|exists:categories,id',
            'original_code' => 'required|string|max:255,unique:products,original_code',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'sometimes|string|max:255',
            'origin' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:2000',
            'brand' => 'required|string|max:255',
            'sale_unit_id' => 'required|exists:sale_units,id',
            'units_per_carton' => 'required|integer|min:1',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,svg|max:2048'
        ];
    }

    public function toDto()
    {
        $data = $this->validated();

        return new CreateProductDto(
            categoryId: $data['category_id'],
            original_code: $data['original_code'],
            name_ar: $data['name_ar'],
            name_en: $data['name_en'] ?? null,
            origin: $data['origin'] ?? null,
            description: $data['description'] ?? null,
            brand: $data['brand'],
            saleUnitId: $data['sale_unit_id'],
            unitsPerCarton: $data['units_per_carton'],
            image: $data['image'] ?? null,
        );
    }
}
