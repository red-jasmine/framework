<?php

namespace RedJasmine\Order\UI\Http\Owner\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @mixin \RedJasmine\Order\Domain\Models\Extensions\OrderExtension
 */
class OrderExtensionResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'seller_message' => $this->seller_message,
            'seller_remarks' => $this->seller_message,
            'buyer_message'  => $this->buyer_message,
            'other_extra'  => $this->other_extra,
            'tools'          => $this->tools,
        ];
    }
}
