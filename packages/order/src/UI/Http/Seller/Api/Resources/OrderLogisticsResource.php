<?php

namespace RedJasmine\Order\UI\Http\Seller\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Order\Domain\Models\OrderLogistics;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin OrderLogistics */
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
            'entity_type'          => $this->entity_type,
            'entity_id'            => $this->entity_id,
            'order_product_id'     => $this->order_product_id,
            'shipper'              => $this->shipper,
            'status'               => $this->status,
            'logistics_company_code' => $this->logistics_company_code,
            'logistics_no'           => $this->logistics_no,
            'shipping_time'        => $this->shipping_time,
            'collect_time'         => $this->collect_time,
            'dispatch_time'        => $this->dispatch_time,
            'signed_time'          => $this->signed_time,
            'extras'              => $this->extras,
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
