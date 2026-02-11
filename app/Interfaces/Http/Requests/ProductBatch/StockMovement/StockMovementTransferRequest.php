<?php

namespace App\Interfaces\Http\Requests\ProductBatch\StockMovement;

use App\Application\DTOs\StockMovementTransferDto;
use Illuminate\Foundation\Http\FormRequest;


class StockMovementTransferRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'product_batch_id' => 'required|exists:product_batches,id',
            'quantity' => 'required|numeric',
            'from_location_id' => 'required|exists:locations,id',
            'to_location_id' => 'required|exists:locations,id',
        ];
    }

    public function toDto()
    {
        $data = $this->validated();

        return new StockMovementTransferDto(
            batchId: $data['product_batch_id'],
            quantity: $data['quantity'],
            fromLocationId: $data['from_location_id'],
            toLocationId: $data['to_location_id'],
        );
    }
}