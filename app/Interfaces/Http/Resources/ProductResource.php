<?php

namespace App\Interfaces\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'description' => $this->description,
            'image' => $this->image,
            'origin' => $this->origin,
            'brand' => $this->brand,
            'units_per_carton' => $this->unitsPerCarton,
        ];

        // Only add category if it exists
        if ($this->category) {
            $data['category'] = new CategoryResource($this->category);
        } elseif ($this->categoryId) {
            $data['category_id'] = $this->categoryId;
        }

        if ($this->saleUnit) {
            $data['sale_unit'] = new SaleUnitResource($this->saleUnit);
        } elseif ($this->saleUnitId) {
            $data['sale_unit_id'] = $this->saleUnitId;
        }

        $data['created_at'] = $this->created_at->format('Y-m-d h:i a');
        $data['updated_at'] = $this->updated_at->format('Y-m-d h:i a');

        return $data;
    }
}
