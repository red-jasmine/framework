<?php

namespace RedJasmine\Order\UI\Http\Seller\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @mixin \RedJasmine\Order\Domain\Models\OrderProductInfo
 */
class OrderProductInfoResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'seller_message' => $this->seller_message,
            'buyer_remarks'  => $this->buyer_remarks,
            'buyer_message'  => $this->buyer_message,
            'buyer_extends'  => $this->buyer_extends,
            'other_extends'  => $this->other_extends,
            'tools'          => $this->tools,
        ];
    }
}
