<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'stock' => $this->stock,
            'category_id' => $this->category_id,
            'image_path' => $this->image_path,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'discounts' => DiscountResource::collection(
                $this->whenLoaded('discounts')
            ),
        ];
    }
}
