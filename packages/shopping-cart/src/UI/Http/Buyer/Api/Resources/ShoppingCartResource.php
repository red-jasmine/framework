<?php

namespace RedJasmine\ShoppingCart\UI\Http\Buyer\Api\Resources;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

class ShoppingCartResource extends JsonResource
{
    public function toArray($request) : array
    {
        return [
            'id'       => $this->id,
            'market'   => $this->market,
            'status'   => $this->status?->value,
            'products' => ShoppingCartProductResource::collection($this->whenLoaded('products', $this->products)),
        ];
    }
}


