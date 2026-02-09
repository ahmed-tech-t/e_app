<?php

namespace App\Interfaces\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductBatchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'batch_code' => $this->batchCode,
            'product_id' => $this->productId,
            'remaining_quantity' => $this->remainingQuantity,
            'initial_quantity' => $this->initialQuantity,
            'cost_price' => $this->costPrice,
            'retail_price' => $this->retailPrice,
            'wholesale_price' => $this->wholesalePrice
        ];
    }
}
