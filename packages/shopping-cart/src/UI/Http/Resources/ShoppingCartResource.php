<?php

namespace RedJasmine\ShoppingCart\UI\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShoppingCartResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'market' => $this->market,
            'status' => $this->status?->value,
            'total_amount' => $this->total_amount,
            'discount_amount' => $this->discount_amount,
            'final_amount' => $this->final_amount,
            'expired_at' => $this->expired_at?->toDateTimeString(),
            'products' => ShoppingCartProductResource::collection($this->whenLoaded('products', $this->products)),
        ];
    }
} 