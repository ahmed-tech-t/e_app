<?php

namespace App\Interfaces\Http\Requests\Product\price;

use App\Application\DTOs\UpdateProductPriceDto;
use App\Infrastructure\Persistence\utils\PriceType;
use App\Interfaces\Http\Requests\BaseRequest;
use App\Utils\ValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductPriceRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'product_id' => $this->route('id')
        ]);
    }

    public function rules()
    {
        return [
            'product_id' => ValidationRules::productId(),
            'price' => ValidationRules::price(),
            'type' => 'required|in:retail,wholesale'
        ];
    }

    public function toDto()
    {
        $data = $this->validated();
        return new UpdateProductPriceDto(
            product_id: $data['product_id'],
            price: $data['price'],
            type: PriceType::from($data['type'])
        );
    }
}