<?php

namespace App\Interfaces\Http\Resources;

use App\Interfaces\Http\Resources\utils\TimeFormate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'code' => $this->code,
            'original_code' => $this->original_code,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'retail_price' => $this->retail_price,
            'wholesale_price' => $this->wholesale_price,
            'description' => $this->description,
            'image' => $this->image,
            'origin' => $this->origin,
            'brand' => $this->brand,
            'units_per_carton' => $this->units_per_carton,
        ];

        // Only add category if it exists
        if ($this->category) {
            $data['category'] = new CategoryResource($this->category);
        } elseif ($this->category_id) {
            $data['category_id'] = $this->category_id;
        }

        if ($this->sale_unit) {
            $data['sale_unit'] = new SaleUnitResource($this->sale_unit);
        } elseif ($this->sale_unit_id) {
            $data['sale_unit_id'] = $this->sale_unit_id;
        }


        if ($this->total_remaining_quantity) {
            $data['total_remaining_quantity'] = $this->total_remaining_quantity;
        }


        $data['created_at'] = $this->created_at->format(TimeFormate::formate());
        $data['updated_at'] = $this->updated_at->format(TimeFormate::formate());

        return $data;
    }
}
