<?php

namespace App\Interfaces\Http\Requests\Product;

use App\Application\DTOs\UpdateProductDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name_ar' => 'sometimes|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'units_per_carton' => 'sometimes|integer|min:1',
            'brand' => 'sometimes|string|max:255',
            'sale_unit_id' => 'sometimes|exists:sale_units,id',
            'category_id' => 'sometimes|exists:categories,id',
            'original_code' => 'nullable|string|max:255,unique:products,original_code',
        ];
    }

    public function toDto()
    {
        $data = $this->validated();

        return new UpdateProductDto(
            categoryId: $data['category_id'] ?? null,
            original_code: $data['original_code'] ?? null,
            name_ar: $data['name_ar'] ?? null,
            name_en: $data['name_en'] ?? null,
            origin: $data['origin'] ?? null,
            description: $data['description'] ?? null,
            brand: $data['brand'] ?? null,
            saleUnitId: $data['sale_unit_id'] ?? null,
            unitsPerCarton: $data['units_per_carton'] ?? null,
            image: $data['image'] ?? null,
        );
    }
}
