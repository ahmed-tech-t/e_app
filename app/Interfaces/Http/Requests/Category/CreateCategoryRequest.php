<?php

namespace App\Interfaces\Http\Requests\Category;

use App\Application\DTOs\CategoryDto;
use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
        ];
    }

    public function toDto()
    {
        $data = $this->validated();

        return new CategoryDto(
            name_ar: $data['name_ar'],
            name_en: $data['name_en']
        );
    }
}