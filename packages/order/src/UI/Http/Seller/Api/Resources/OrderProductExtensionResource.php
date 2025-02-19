<?php

namespace RedJasmine\Order\UI\Http\Seller\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @mixin \RedJasmine\Order\Domain\Models\Extensions\OrderProductExtension
 */
class OrderProductExtensionResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'seller_message' => $this->seller_message,
            'seller_remarks' => $this->seller_remarks,
            'buyer_message'  => $this->buyer_message,
            'seller_expands' => $this->seller_expands,
            'other_expands'  => $this->other_expands,
            'tools'          => $this->tools,
        ];
    }
}
