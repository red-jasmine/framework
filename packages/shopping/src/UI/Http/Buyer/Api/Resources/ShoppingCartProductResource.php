<?php

namespace RedJasmine\Shopping\UI\Http\Buyer\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShoppingCartProductResource extends JsonResource
{
    public function toArray($request) : array
    {
        return [
            'id'       => $this->id,
            'cart_id'  => $this->cart_id,
            'quantity' => $this->quantity,
            'price'    => $this->price,
            'subtotal' => $this->subtotal,
            'selected' => $this->selected,
            'product'  => $this->productInfo,
        ];
    }
} 