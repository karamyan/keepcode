<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order id' => $this->id,
            'amount' => $this->amount,
            'products' => $this->products->transform(function ($product) {
                return new ProductResource($product);
            }),
            'created date' => $this->created_at
        ];
    }
}
