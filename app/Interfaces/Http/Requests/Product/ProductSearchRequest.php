<?php

namespace App\Interfaces\Http\Requests\Product;

use App\Application\DTOs\ProductSearchDto;
use App\Utils\ValidationRules;
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
            'name_ar' => ValidationRules::name(false),
            'name_en' => ValidationRules::name(false),
            'brand' => ValidationRules::name(false),
            'original_code' => ValidationRules::name(false),
            'code' => ValidationRules::name(false),
            'category_id' => ValidationRules::categoryId(false),
            'sale_unit_id' => ValidationRules::saleUnitId(false),
            'location_id' => ValidationRules::locationId(false),
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
            $data['location_id'] ?? null
        );
    }

}