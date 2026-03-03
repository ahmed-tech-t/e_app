<?php

namespace App\Interfaces\Http\Resources;
use App\Interfaces\Http\Resources\utils\TimeFormate;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductPriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'price' => $this->price,
            'type' => $this->type,
            'valid_to' => $this->validTo?->format(TimeFormate::formate()),
            'valid_from' => $this->validFrom?->format(TimeFormate::formate()),
        ];
    }

}