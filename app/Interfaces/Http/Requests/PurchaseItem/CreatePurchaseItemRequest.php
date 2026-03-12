<?php

namespace App\Interfaces\Http\Requests\PurchaseItem;

use App\Application\DTOs\PurchaseItemDto;
use App\Utils\ValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class CreatePurchaseItemRequest extends FormRequest
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
            'product_id' => ValidationRules::productId(),
            'quantity' => ValidationRules::quantity(),
            'price' => ValidationRules::price(),
        ];
    }

    public function toDto()
    {
        $data = $this->validated();
        return new PurchaseItemDto(
            product_id: $data['product_id'],
            quantity: $data['quantity'],
            price: $data['price']
        );
    }
}
