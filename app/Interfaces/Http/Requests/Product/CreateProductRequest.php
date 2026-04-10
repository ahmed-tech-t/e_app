<?php

namespace App\Interfaces\Http\Requests\Product;

use App\Application\DTOs\CreateProductDto;
use App\Utils\ValidationRules;
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
            'category_id' => ValidationRules::categoryId(),
            'original_code' => ValidationRules::name(false) . '|unique:products,original_code',
            'name_ar' => ValidationRules::name(),
            'name_en' => ValidationRules::name(false),
            'origin' => ValidationRules::name(false),
            'description' => 'nullable|string|max:2000',
            'brand' => ValidationRules::name(),
            'sale_unit_id' => ValidationRules::saleUnitId(),
            'units_per_carton' => ValidationRules::quantity(false),
            'image' => ValidationRules::image(false),
            'retail_price' => ValidationRules::price(),
            'wholesale_price' => ValidationRules::price(),
        ];
    }

    public function toDto()
    {
        $data = $this->validated();

        return new CreateProductDto(
            category_id: $data['category_id'],
            original_code: $data['original_code'] ?? null,
            name_ar: $data['name_ar'],
            name_en: $data['name_en'] ?? null,
            origin: $data['origin'] ?? null,
            description: $data['description'] ?? null,
            brand: $data['brand'],
            sale_unit_id: $data['sale_unit_id'],
            units_per_carton: $data['units_per_carton'] ?? null,
            image: $data['image'] ?? null,
            retail_price: $data['retail_price'],
            wholesale_price: $data['wholesale_price'],
        );
    }
}
