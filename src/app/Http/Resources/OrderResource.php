<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'address_id' => $this->address_id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'total_amount' => $this->total_amount
        ];
    }
}
