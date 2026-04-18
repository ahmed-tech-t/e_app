<?php

namespace App\Interfaces\Http\Requests\Sales;

use App\Application\DTOs\SalesDto;
use App\Application\DTOs\SalesItemDto;
use App\Infrastructure\Persistence\utils\PriceType;
use App\Utils\ValidationRules;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rules\Enum;


class CreateSalesRequest extends FormRequest
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
            'customer_name' => ValidationRules::name(),
            'customer_phone' => ValidationRules::name(false),
            'store_id' => ValidationRules::locationId(),
            'bill_type' => ['required', new Enum(PriceType::class)],
            'items' => ValidationRules::array(),
            'items.*.product_id' => ValidationRules::productId(),
            'items.*.quantity' => ValidationRules::quantity(),
            'discount' => ValidationRules::percentage(false),
        ];
    }
    public function toDto(): SalesDto
    {
        $data = $this->validated();
        return new SalesDto(
            customer_name: $data['customer_name'],
            store_id: $data['store_id'],
            type: PriceType::tryFrom($data['bill_type']),
            items: collect($data['items'])
                ->map(fn(array $item) => SalesItemDto::create($item))
                ->toArray(),
            discount: $data['discount'],
            customer_phone: $data['customer_phone'] ?? null,
        );
    }
}
