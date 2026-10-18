<?php

namespace App\Interfaces\Http\Requests\PurchaseReturn;

use App\Application\DTOs\PurchaseReturnDto;
use App\Application\DTOs\PurchaseReturnItemDto;
use App\Domain\Utils\ReturnedItemStatus;
use App\Rules\CheckPurchaseReturnQuantity;
use App\Utils\ValidationRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreatePurchaseReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'purchase_id' => 'required|integer|exists:purchases,id',
            'store_id' => ValidationRules::locationId(),
            'items.*.product_id' => ValidationRules::productId(),
            'items.*.quantity' => ValidationRules::quantity(),
            'items.*.reason' => 'nullable|string',
            'items.*.restock_status' => ['required', Rule::enum(ReturnedItemStatus::class)],
            'items' => [
                'required',
                'array',
                'min:1',
                new CheckPurchaseReturnQuantity($this->purchase_id, $this->store_id),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('items') && is_array($this->items)) {
            $filtered = array_filter($this->items, function ($item) {
                return isset($item['quantity']) && $item['quantity'] !== null && $item['quantity'] !== '';
            });

            $this->merge([
                'items' => array_values($filtered),
            ]);
        }
    }

    public function messages(): array
    {
        return [
            'items.required' => __('app.common.select_at_least_one'),
            'items.min' => __('app.common.select_at_least_one'),
        ];
    }

    public function toDto(): PurchaseReturnDto
    {
        $data = $this->validated();

        return new PurchaseReturnDto(
            purchase_id: $data['purchase_id'],
            store_id: $data['store_id'],
            items: collect($data['items'])
                ->map(fn ($item) => PurchaseReturnItemDto::create($item))
                ->toArray(),
        );
    }
}
