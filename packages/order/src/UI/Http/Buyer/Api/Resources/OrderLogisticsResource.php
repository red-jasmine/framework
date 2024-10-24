<?php

namespace RedJasmine\Order\UI\Http\Buyer\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin \RedJasmine\Order\Domain\Models\OrderLogistics */
class OrderLogisticsResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'                   => $this->id,
            'seller_type'          => $this->seller_type,
            'seller_id'            => $this->seller_id,
            'buyer_type'           => $this->buyer_type,
            'buyer_id'             => $this->buyer_id,
            'shippable_type'       => $this->shippable_type,
            'shippable_id'         => $this->shippable_id,
            'order_product_id'     => $this->order_product_id,
            'shipper'              => $this->shipper,
            'status'               => $this->status,
            'express_company_code' => $this->express_company_code,
            'express_no'           => $this->express_no,
            'shipping_time'        => $this->shipping_time,
            'collect_time'         => $this->collect_time,
            'dispatch_time'        => $this->dispatch_time,
            'signed_time'          => $this->signed_time,
            'version'              => $this->version,
            'creator_id'           => $this->creator_id,
            'creator_type'         => $this->creator_type,
            'updater_id'           => $this->updater_id,
            'updater_type'         => $this->updater_type,
            'created_at'           => $this->created_at,
            'updated_at'           => $this->updated_at,
        ];
    }
}
