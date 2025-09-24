<?php

namespace RedJasmine\Order\UI\Http\User\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Order\Domain\Models\Refund;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin Refund */
class OrderRefundResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'                     => $this->id,
            'refund_no'              => $this->refund_no,
            'order_no'               => $this->order_no,
            'biz'                    => $this->biz,
            'seller_type'            => $this->seller_type,
            'seller_id'              => $this->seller_id,
            'buyer_type'             => $this->buyer_type,
            'buyer_id'               => $this->buyer_id,
            'shipping_type'          => $this->shipping_type,
            'order_product_type'     => $this->order_product_type,
            'title'                  => $this->title,
            'sku_name'               => $this->sku_name,
            'image'                  => $this->image,
            'product_type'           => $this->product_type,
            'product_id'             => $this->product_id,
            'sku_id'                 => $this->sku_id,
            'category_id'            => $this->category_id,
            'product_group_id'       => $this->product_group_id,
            'outer_product_id'       => $this->outer_product_id,
            'outer_sku_id'           => $this->outer_sku_id,
            'barcode'                => $this->barcode,
            'quantity'               => $this->quantity,
            'price'                  => $this->price,
            // 'cost_price'             => $this->cost_price,
            'product_amount'         => $this->product_amount,
            'tax_amount'             => $this->tax_amount,
            'discount_amount'        => $this->discount_amount,
            'payable_amount'         => $this->payable_amount,
            'payment_amount'         => $this->payment_amount,
            'divided_payment_amount' => $this->divided_payment_amount,
            'phase'                  => $this->phase,
            'refund_type'            => $this->refund_type,
            'freight_amount'         => $this->freight_amount,
            'has_good_return'        => $this->has_good_return,
            'good_status'            => $this->good_status,
            'reason'                 => $this->reason,
            'description'            => $this->description,
            'images'                 => $this->images,
            'outer_refund_id'        => $this->outer_refund_id,
            'refund_status'          => $this->refund_status,
            'refund_amount'          => $this->refund_amount,
            'reject_reason'          => $this->reject_reason,
            'created_time'           => $this->created_time,
            'end_time'               => $this->end_time,
            // 'seller_custom_status'   => $this->seller_custom_status,
            'seller_remarks'         => $this->seller_remarks,
            'buyer_remarks'          => $this->buyer_remarks,
            'extends'                => $this->extends,
            'version'                => $this->version,
            'creator_id'             => $this->creator_id,
            'creator_type'           => $this->creator_type,
            'updater_id'             => $this->updater_id,
            'updater_type'           => $this->updater_type,
            'created_at'             => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'             => $this->updated_at?->format('Y-m-d H:i:s'),
            'order_id'               => $this->order_id,
            'order_product_id'       => $this->order_product_id,

            'logistics' => OrderLogisticsResource::collection($this->whenLoaded('logistics')),
            'order'     => new OrderResource($this->whenLoaded('order')),
            'product'   => new OrderProductResource($this->whenLoaded('product')),
            'payments'  => OrderPaymentResource::collection($this->whenLoaded('payments')),
        ];
    }
}
