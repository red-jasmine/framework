<?php

namespace RedJasmine\Order\UI\Http\Owner\Api\Resources;

use Illuminate\Http\Request;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @mixin \RedJasmine\Order\Domain\Models\Order
 */
class OrderResource extends JsonResource
{

    public function toArray(Request $request) : array
    {
        return [
            'id'                     => $this->id,
            'seller_type'            => $this->seller_type,
            'seller_id'              => $this->seller_id,
            'seller_nickname'        => $this->seller_nickname,
            'buyer_type'             => $this->buyer_type,
            'buyer_id'               => $this->buyer_id,
            'buyer_nickname'         => $this->buyer_nickname,
            'title'                  => $this->title,
            'order_type'             => $this->order_type,
            'pay_type'               => $this->pay_type,
            'product_amount'         => $this->product_amount,
            'cost_amount'            => $this->cost_amount,
            'tax_amount'             => $this->tax_amount,
            'product_payable_amount' => $this->product_payable_amount,
            'discount_amount'        => $this->discount_amount,
            'freight_amount'         => $this->freight_amount,
            'payable_amount'         => $this->payable_amount,
            'payment_amount'         => $this->payment_amount,
            'refund_amount'          => $this->refund_amount,
            'commission_amount'      => $this->commission_amount,
            'outer_order_id'         => $this->outer_order_id,
            'channel_type'           => $this->channel_type,
            'channel_id'             => $this->channel_id,
            'store_type'             => $this->store_type,
            'store_id'               => $this->store_id,
            'guide_type'             => $this->guide_type,
            'guide_id'               => $this->guide_id,
            'client_type'            => $this->client_type,
            'client_version'         => $this->client_version,
            'client_ip'              => $this->client_ip,
            'source_type'            => $this->source_type,
            'source_id'              => $this->source_id,
            'contact'                => $this->contact,
            'version'                => $this->version,
            'order_status'           => $this->order_status,
            'payment_status'         => $this->payment_status,
            'shipping_status'        => $this->shipping_status,
            'refund_status'          => $this->refund_status,
            'seller_custom_status'   => $this->seller_custom_status,
            'rate_status'            => $this->rate_status,
            'created_time'           => $this->created_time?->format('Y-m-d H:i:s'),
            'payment_time'           => $this->payment_time?->format('Y-m-d H:i:s'),
            'close_time'             => $this->close_time?->format('Y-m-d H:i:s'),
            'shipping_time'          => $this->shipping_time?->format('Y-m-d H:i:s'),
            'collect_time'           => $this->collect_time?->format('Y-m-d H:i:s'),
            'dispatch_time'          => $this->dispatch_time?->format('Y-m-d H:i:s'),
            'signed_time'            => $this->signed_time?->format('Y-m-d H:i:s'),
            'confirm_time'           => $this->confirm_time?->format('Y-m-d H:i:s'),
            'refund_time'            => $this->refund_time?->format('Y-m-d H:i:s'),
            'rate_time'              => $this->rate_time?->format('Y-m-d H:i:s'),
            'settlement_time'        => $this->settlement_time?->format('Y-m-d H:i:s'),
            'created_at'             => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'             => $this->updated_at?->format('Y-m-d H:i:s'),
            'creator_type'           => $this->creator_type,
            'creator_id'             => $this->creator_id,
            'updater_type'           => $this->updater_type,
            'updater_id'             => $this->updater_id,
            'extension'              => new OrderExtensionResource($this->whenLoaded('extension')),
            'address'                => new OrderAddressResource($this->whenLoaded('address')),
            'products'               => OrderProductResource::collection($this->whenLoaded('products')),
            'payments'               => OrderPaymentResource::collection($this->whenLoaded('payments')),
            'logistics'              => OrderLogisticsResource::collection($this->whenLoaded('logistics')),
        ];
    }
}
