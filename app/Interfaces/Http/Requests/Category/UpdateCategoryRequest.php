<?php

namespace App\Interfaces\Http\Requests\Category;

use App\Application\DTOs\CategoryDto;
use App\Interfaces\Http\Utils\RequestInterface;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name_ar' => 'sometimes|string|max:255',
            'name_en' => 'sometimes|string|max:255',
        ];
    }

    public function toDto()
    {
        $data = $this->validated();

        return new CategoryDto(
            name_ar: $data['name_ar'] ?? null,
            name_en: $data['name_en'] ?? null
        );
    }
}