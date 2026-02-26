<?php

namespace App\Interfaces\Http\Requests\StockMovement;

use App\Utils\ValidationRules;
use Illuminate\Foundation\Http\FormRequest;
use App\Application\DTOs\TransferProductDto;

class TransferProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => ValidationRules::productId(),
            'from_location_id' => ValidationRules::locationId(),
            'to_location_id' => [ValidationRules::locationId(), 'different:from_location_id'],
            'quantity' => ValidationRules::quantity(),
        ];
    }

    public function toDto(): TransferProductDto
    {
        $data = $this->validated();

        return new TransferProductDto(
            productId: $data['product_id'],
            fromLocationId: $data['from_location_id'],
            toLocationId: $data['to_location_id'],
            quantity: $data['quantity'],
        );
    }
}

