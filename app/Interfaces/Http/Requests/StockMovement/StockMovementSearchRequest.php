<?php

namespace App\Interfaces\Http\Requests\StockMovement;

use App\Application\DTOs\StockMovementSearchDto;
use App\Infrastructure\Persistence\utils\StockMovementType;
use App\Utils\ValidationRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Spatie\LaravelData\Support\Validation\ValidationRule;

class StockMovementSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ValidationRules::productId(false),
            'location_id' => ValidationRules::locationId(false),
            'product_batch_id' => ValidationRules::productBatchId(false),
            'type' => ['sometimes', new Enum(StockMovementType::class)],
            'bill_number' => ValidationRules::name(false)
        ];
    }

    public function toDto()
    {
        $data = $this->validated();
        return new StockMovementSearchDto(
            product_id: $data['product_id'] ?? null,
            location_id: $data['location_id'] ?? null,
            product_batch_id: $data['product_batch_id'] ?? null,
            type: StockMovementType::tryFrom($data['type'] ?? null),
            bill_number: $data['bill_number'] ?? null
        );
    }
}