<?php

namespace App\Interfaces\Http\Requests\SalesReturns;

use App\Application\DTOs\SalesReturnItemDto;
use App\Application\DTOs\SalesReturnsDto;
use App\Domain\Utils\ReturnedItemStatus;
use App\Rules\CheckReturnQuantity;
use App\Utils\ValidationRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class CreateSalesReturnsRequest extends FormRequest
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
            'sales_id' => 'required|integer|exists:sales,id',
            'store_id' => ValidationRules::locationId(),
            'items.*.product_id' => ValidationRules::productId(),
            'items.*.quantity' => ValidationRules::quantity(),
            'items.*.reason' => 'nullable|string',
            'items.*.restock_status' => ['required', Rule::enum(ReturnedItemStatus::class)],
            'items' => [
                'required',
                'array',
                'min:1',
                new CheckReturnQuantity($this->sales_id)
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

    public function toDto(): SalesReturnsDto
    {
        $data = $this->validated();
        return new SalesReturnsDto(
            sales_id: $data['sales_id'],
            store_id: $data['store_id'],
            items: collect($data['items'])
                ->map(fn($item) => SalesReturnItemDto::create($item))
                ->toArray(),
        );
    }

}
