<?php

namespace RedJasmine\Shopping\UI\Http\Buyer\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Ecommerce\Domain\Data\Order\OrderData;
use RedJasmine\Support\Domain\Data\UserData;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @mixin OrderData
 */
class OrderDataResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'title'    => $this->title,
            'seller'   => $this->seller,
            'order_no' => $this->getOrderNo(),
            'buyer'    => (UserData::fromUserInterface($this->buyer))->toArray(),

            'payable_amount'    => $this->getOrderAmountInfo()->productAmount,
            'tax_amount'        => $this->getOrderAmountInfo()->taxAmount,
            'service_amount'    => $this->getOrderAmountInfo()->serviceAmount,
            'freight_amount'    => $this->getOrderAmountInfo()->freightAmount,
            'discount_amount'   => $this->getOrderAmountInfo()->discountAmount,
            'payable_amount'    => $this->getOrderAmountInfo()->payableAmount,
            'coupons'           => $this->getOrderAmountInfo()->coupons,
            'available_coupons' => $this->getOrderAmountInfo()->availableCoupons,
            'outer_order_id'    => $this->outerOrderId,
            'products'          => OrderProductDataResource::collection($this->products)

        ];
    }
}
