<?php

namespace RedJasmine\Shopping\UI\Http\Buyer\Api\Resources;

use Illuminate\Http\Request;

use RedJasmine\Ecommerce\Domain\Data\Order\OrderProductData;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @mixin OrderProductData
 */
class OrderProductDataResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'order_product_no' => $this->getOrderProductNo(),
            'serial_number'    => $this->getSerialNumber(),
            'quantity'         => $this->quantity,
            'customized'       => $this->customized,
            'shopping_cart_id' => $this->shoppingCartId,
            $this->merge(new ProductInfoResource($this->getProductInfo()))
        ];

    }
}