<?php

namespace App\Interfaces\Http\Requests\ProductBatch;

use App\Application\DTOs\ProductBatchDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
class UpdateProductBatchRequest extends FormRequest
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
            'remaining_quantity' => 'sometimes|integer|min:0',
            'cost_price' => 'sometimes|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'retail_price' => 'sometimes|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'wholesale_price' => 'sometimes|numeric|regex:/^\d+(\.\d{1,2})?$/',
        ];
    }

    public function toDto(): ProductBatchDto
    {
        $data = $this->validated();
        Log::info("UpdateProductBatchRequest", ['data' => $data]);
        return new ProductBatchDto(
            remainingQuantity: $data['remaining_quantity'] ?? null,
            costPrice: $data['cost_price'] ?? null,
            retailPrice: $data['retail_price'] ?? null,
            wholesalePrice: $data['wholesale_price'] ?? null,
        );
    }
}
