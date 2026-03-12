<?php

namespace App\Interfaces\Http\Requests\Purchase;

use App\Application\DTOs\PurchaseDto;
use App\Utils\ValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class CreatePurchaseRequest extends FormRequest
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
            'supplier_id' => ValidationRules::supplier(),
            'store_id' => ValidationRules::locationId(),
            'tax' => ValidationRules::percentage(false),
            'discount' => ValidationRules::percentage(false),
            'paper_total' => ValidationRules::price(),

            'items' => ValidationRules::array(),
            'items.*.product_id' => ValidationRules::productId(),
            'items.*.quantity' => ValidationRules::quantity(),
            'items.*.price' => ValidationRules::price(),
        ];
    }

    public function toDto()
    {
        $data = $this->validated();
        return new PurchaseDto(
            supplier_id: $data['supplier_id'],
            store_id: $data['store_id'],
            tax: $data['tax'] ?? 0,
            discount: $data['discount'] ?? 0,
            paper_total: $data['paper_total'],
            items: $data['items']
        );
    }
}
