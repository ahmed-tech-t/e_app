<?php

namespace App\Interfaces\Http\Requests\Product;

use App\Application\DTOs\ProductSearchDto;
use Illuminate\Foundation\Http\FormRequest;

class ProductSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|max:100',
            'name_ar' => 'sometimes|string|max:255',
            'name_en' => 'sometimes|string|max:255',
            'brand' => 'sometimes|string|max:255',
            'original_code' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:255',
            'category_id' => 'sometimes|integer|exists:categories,id',
            'sale_unit_id' => 'sometimes|integer|exists:sale_units,id',
        ];
    }

    public function toDto()
    {
        $data = $this->validated();

        return new ProductSearchDto(
            $data['page'] ?? null,
            $data['per_page'] ?? null,
            $data['name_ar'] ?? null,
            $data['name_en'] ?? null,
            $data['brand'] ?? null,
            $data['original_code'] ?? null,
            $data['code'] ?? null,
            $data['category_id'] ?? null,
            $data['sale_unit_id'] ?? null,
        );
    }

}