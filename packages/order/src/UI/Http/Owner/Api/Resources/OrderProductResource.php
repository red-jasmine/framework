<?php

namespace RedJasmine\Order\UI\Http\Seller\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;


/**
 * @mixin \RedJasmine\Order\Domain\Models\OrderProduct
 */
class OrderProductResource extends JsonResource
{


    public function toArray(Request $request) : array
    {
        return [
            'id'                      => $this->id,
            'shipping_type'           => $this->shipping_type,
            'order_product_type'      => $this->order_product_type,
            'title'                   => $this->title,
            'sku_name'                => $this->sku_name,
            'image'                   => $this->image,
            'product_type'            => $this->product_type,
            'product_id'              => $this->product_id,
            'sku_id'                  => $this->sku_id,
            'barcode'                 => $this->barcode,
            'quantity'                     => $this->quantity,
            'unit'                    => $this->unit,
            'price'                   => $this->price,
            'cost_price'              => $this->cost_price,
            'cost_amount'             => $this->cost_amount,
            'product_amount'          => $this->product_amount,
            'tax_amount'              => $this->tax_amount,
            'discount_amount'         => $this->discount_amount,
            'payable_amount'          => $this->payable_amount,
            'payment_amount'          => $this->payment_amount,
            'divided_discount_amount' => $this->divided_discount_amount,
            'divided_payment_amount'  => $this->divided_payment_amount,
            'refund_amount'           => $this->refund_amount,
            'commission_amount'       => $this->commission_amount,

            'progress'               => $this->progress,
            'progress_total'         => $this->progress_total,
            'gift_point' => $this->gift_point,
            'outer_order_product_id' => $this->outer_order_product_id,

            'version'              => $this->version,
            'order_status'         => $this->order_status,
            'payment_status'       => $this->payment_status,
            'shipping_status'      => $this->shipping_status,
            'refund_status'        => $this->refund_status,
            'seller_custom_status' => $this->seller_custom_status,
            'rate_status'          => $this->rate_status,
            'created_time'         => $this->created_time?->format('Y-m-d H:i:s'),
            'payment_time'         => $this->payment_time?->format('Y-m-d H:i:s'),
            'close_time'           => $this->close_time?->format('Y-m-d H:i:s'),
            'shipping_time'        => $this->shipping_time?->format('Y-m-d H:i:s'),
            'collect_time'         => $this->collect_time?->format('Y-m-d H:i:s'),
            'dispatch_time'        => $this->dispatch_time?->format('Y-m-d H:i:s'),
            'signed_time'          => $this->signed_time?->format('Y-m-d H:i:s'),
            'confirm_time'         => $this->confirm_time?->format('Y-m-d H:i:s'),
            'refund_time'          => $this->refund_time?->format('Y-m-d H:i:s'),
            'rate_time'            => $this->rate_time?->format('Y-m-d H:i:s'),
            'created_at'           => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'           => $this->updated_at?->format('Y-m-d H:i:s'),
            'creator_type'         => $this->creator_type,
            'creator_id'           => $this->creator_id,
            'updater_type'         => $this->updater_type,
            'updater_id'           => $this->updater_id,
            'extension'                 => new OrderProductExtensionResource($this->whenLoaded('extension')),
        ];
    }
}

