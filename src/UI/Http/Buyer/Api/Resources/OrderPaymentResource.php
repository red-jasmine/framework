<?php

namespace RedJasmine\Order\UI\Http\Buyer\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;


/** @mixin \RedJasmine\Order\Domain\Models\OrderPayment */
class OrderPaymentResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'                 => $this->id,
            'refund_id'          => $this->refund_id,
            'seller_type'        => $this->seller_type,
            'seller_id'          => $this->seller_id,
            'buyer_type'         => $this->buyer_type,
            'buyer_id'           => $this->buyer_id,
            'amount_type'        => $this->amount_type,
            'payment_amount'     => $this->payment_amount,
            'status'             => $this->status,
            'payment_time'       => $this->payment_time,
            'payment_type'       => $this->payment_type,
            'payment_id'         => $this->payment_id,
            'payment_method'     => $this->payment_method,
            'payment_channel'    => $this->payment_channel,
            'payment_channel_no' => $this->payment_channel_no,
            'version'            => $this->version,
            'creator_id'         => $this->creator_id,
            'creator_type'       => $this->creator_type,
            'updater_id'         => $this->updater_id,
            'updater_type'       => $this->updater_type,
            'created_at'         => $this->created_at,
            'updated_at'         => $this->updated_at,

            'order_id' => $this->order_id,

            'order' => new OrderResource($this->whenLoaded('order')),
        ];
    }
}
