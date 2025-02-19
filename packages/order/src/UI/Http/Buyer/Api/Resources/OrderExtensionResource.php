<?php

namespace RedJasmine\Order\UI\Http\Buyer\Api\Resources;

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
            'buyer_remarks'  => $this->buyer_remarks,
            'buyer_message'  => $this->buyer_message,
            'buyer_expands'  => $this->buyer_expands,
            'other_extends'  => $this->other_expands,
            'tools'          => $this->tools,
        ];
    }
}
