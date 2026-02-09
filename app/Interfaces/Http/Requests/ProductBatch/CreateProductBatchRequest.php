<?php

namespace App\Interfaces\Http\Requests\ProductBatch;

use App\Application\DTOs\ProductBatchDto;
use Illuminate\Foundation\Http\FormRequest;

class CreateProductBatchRequest extends FormRequest
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
            'batch_code' => 'required|string|max:255',
            'product_id' => 'required|exists:products,id',
            'initial_quantity' => 'required|integer|min:1',
            'cost_price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'retail_price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'wholesale_price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
        ];
    }

    public function toDto()
    {
        $data = $this->validated();

        return new ProductBatchDto(
            batchCode: $data['batch_code'],
            productId: $data['product_id'],
            initialQuantity: $data['initial_quantity'],
            remainingQuantity: $data['initial_quantity'],
            costPrice: $data['cost_price'],
            retailPrice: $data['retail_price'],
            wholesalePrice: $data['wholesale_price'],
        );
    }
}
