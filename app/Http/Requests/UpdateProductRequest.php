<?php

namespace App\Http\Requests;

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
            'code' => 'sometimes|string|max:255|unique:products,code,' . $this->product->id,
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:2000',
            'price' => 'sometimes|numeric|min:0|max:1000000|decimal:0,2',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,svg|max:2048',
            'origin' => 'sometimes|string|max:255',
        ];
    }
}
