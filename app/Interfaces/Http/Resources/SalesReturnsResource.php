<?php

namespace App\Interfaces\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesReturnsResource extends JsonResource
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
            'code' => $this->code,
            'sales_id' => $this->sales_id,
            'store_id' => $this->store_id,
            'items' => SalesReturnItemResource::collection($this->items),
            'created_at' => $this->created_at,
        ];
    }
}
