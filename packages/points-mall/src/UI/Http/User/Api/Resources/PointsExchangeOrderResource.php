<?php

namespace RedJasmine\PointsMall\UI\Http\User\Api\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RedJasmine\PointsMall\Domain\Models\PointsExchangeOrder;

/** @mixin PointsExchangeOrder */
class PointsExchangeOrderResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'                    => $this->id,
            'points_order_no'       => $this->points_order_no,
            'owner_type'            => $this->owner_type,
            'owner_id'              => $this->owner_id,
            'outer_order_no'        => $this->outer_order_no,
            'title'                 => $this->title,
            'image'                 => $this->image,
            'product_type'          => $this->product_type,
            'product_id'            => $this->product_id,
            'sku_id'                => $this->sku_id,
            'point'                 => $this->point,
            'price'                 => $this->price,
            'price_currency'        => $this->price_currency,
            'price_amount'          => $this->price_amount,
            'quantity'              => $this->quantity,
            'total_point'           => $this->total_point,
            'total_amount'          => $this->total_amount,
            'total_amount_currency' => $this->total_amount_currency,
            'total_amount_amount'   => $this->total_amount_amount,
            'status'                => $this->status,
            'exchange_time'         => $this->exchange_time,
            'price'                 => $this->price,
            'total_amount'          => $this->total_amount,
            'point_product_id'      => $this->point_product_id,


        ];
    }
}
