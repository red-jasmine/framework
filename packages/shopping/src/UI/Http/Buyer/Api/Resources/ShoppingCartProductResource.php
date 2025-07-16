<?php

namespace RedJasmine\Shopping\UI\Http\Buyer\Api\Resources;


use RedJasmine\Shopping\Domain\Models\ShoppingCartProduct;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @mixin ShoppingCartProduct
 */
class ShoppingCartProductResource extends JsonResource
{
    public function toArray($request) : array
    {
        return [
            // 保持在购物车中的信息
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
            // 最新的商品信息

            $this->merge(new ProductInfoResource($this->product))

        ];
    }
} 