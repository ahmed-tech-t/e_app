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
            'purchase_id'              => 'required|integer|exists:purchases,id',
            'store_id'                 => ValidationRules::locationId(),
            'items.*.product_id'       => ValidationRules::productId(),
            'items.*.quantity'         => ValidationRules::quantity(),
            'items.*.reason'           => 'nullable|string',
            'items.*.restock_status'   => ['required', Rule::enum(ReturnedItemStatus::class)],
            'items'                    => [
                'required',
                'array',
                'min:1',
                new CheckPurchaseReturnQuantity($this->purchase_id, $this->store_id),
            ],
        ];
    }

    public function toDto(): PurchaseReturnDto
    {
        $data = $this->validated();

        return new PurchaseReturnDto(
            purchase_id: $data['purchase_id'],
            store_id: $data['store_id'],
            items: collect($data['items'])
                ->map(fn($item) => PurchaseReturnItemDto::create($item))
                ->toArray(),
        );
    }
}
