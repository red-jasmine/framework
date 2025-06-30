<?php

namespace RedJasmine\ShoppingCart\UI\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShoppingCartProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'cart_id' => $this->cart_id,
            'identity' => $this->identity?->toArray(),
            'quantity' => $this->quantity,
            'price' => $this->price,
            'original_price' => $this->original_price,
            'discount_amount' => $this->discount_amount,
            'subtotal' => $this->subtotal,
            'selected' => $this->selected,
            'properties' => $this->properties,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
} 