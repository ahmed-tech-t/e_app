<?php

namespace App\Interfaces\Http\Resources;

use App\Interfaces\Http\Resources\utils\TimeFormate;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class StockMovementResource extends JsonResource
{
    public function toArray($request)
    {

        $data = [
            'id' => $this->id,
        ];

        if ($this->product_batch)
            $data['product_batch'] = new ProductBatchResource($this->product_batch);
        else
            $data['product_batch_id'] = $this->product_batch_id;



        if ($this->location)
            $data['location'] = new LocationResource($this->location);
        else
            $data['location_id'] = $this->location_id;

        $data['quantity'] = $this->quantity;
        $data['type'] = $this->type;
        $data['bill_number'] = $this->bill_number;
        $data['created_at'] = $this->created_at->format(TimeFormate::formate());


        return $data;

    }
}