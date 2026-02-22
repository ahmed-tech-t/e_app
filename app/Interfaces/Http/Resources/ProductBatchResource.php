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
        if (is_null($this->resource)) {
            return [];
        }
        $data = [
            'id' => $this->id,
            'batch_code' => $this->batchCode,
            'remaining_quantity' => $this->remainingQuantity,
            'initial_quantity' => $this->initialQuantity,
            'cost_price' => $this->costPrice,
        ];

        if ($this->product) {
            $data['product'] = $this->product;
        } else {
            $data['product_id'] = $this->productId;
        }

        if ($this->locations) {
            $data['locations'] = $this->locations;
        }
        return $data;
    }
}
