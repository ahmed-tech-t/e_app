<?php

namespace App\Interfaces\Http\Requests\Product\price;

use App\Infrastructure\Persistence\utils\PriceType;
use App\Utils\ValidationRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ProductPriceHistoryRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'product_id' => $this->route('id')
        ]);
    }

    public function rules()
    {
        return [
            'product_id' => ValidationRules::productId(),
            'type' => ['nullable', new Enum(PriceType::class)],
        ];
    }
}