<?php

namespace RedJasmine\ShoppingCart\UI\Http\Buyer\Api\Resources;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

class ShoppingCartProductResource extends JsonResource
{
    public function toArray($request) : array
    {
        return [
            'id'              => $this->id,
            'cart_id'         => $this->cart_id,
            'selected'        => $this->selected,
            'quantity'        => $this->quantity,
            'seller_type'     => $this->seller_type,
            'seller_id'       => $this->seller_id,
            'seller_nickname' => $this->seller_nickname,
            'product_type'    => $this->product_type,
            'product_id'      => $this->product_id,
            'sku_id'          => $this->sku_id,
            'customized'      => $this->customized,
            'currency'        => $this->currency,
            'title'           => $this->title,
            'properties_name' => $this->properties_name,
            'image'           => $this->image,
            'price'           => $this->price,
        ];
    }
}


