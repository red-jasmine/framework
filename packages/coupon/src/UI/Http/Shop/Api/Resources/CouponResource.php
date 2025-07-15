<?php

namespace RedJasmine\Coupon\UI\Http\Shop\Api\Resources;

use Illuminate\Http\Request;

use RedJasmine\Coupon\Domain\Models\Coupon;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin Coupon */
class CouponResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'                    => $this->id,
            'owner_type'            => $this->owner_type,
            'owner_id'              => $this->owner_id,
            'name'                  => $this->name,
            'description'           => $this->description,
            'label'                 => $this->label,
            'image'                 => $this->image,
            'is_show'               => $this->is_show,
            'status'                => $this->status,
            'discount_level'       => $this->discount_level,
            'threshold_type'        => $this->threshold_type,
            'threshold_value'       => $this->threshold_value,
            'discount_amount_type'  => $this->discount_amount_type,
            'discount_amount_value' => $this->discount_amount_value,
            'max_discount_amount'   => $this->max_discount_amount,
            'validity_type'         => $this->validity_type,
            'validity_start_time'   => $this->validity_start_time,
            'validity_end_time'     => $this->validity_end_time,
            'delayed_effective_time' => $this->delayed_effective_time,
            'validity_time'         => $this->validity_time,
            'usage_rules'           => $this->usage_rules,
            'receive_rules'         => $this->receive_rules,
            'sort'                  => $this->sort,
            'remarks'               => $this->remarks,
            'total_quantity'        => $this->total_quantity,
            'total_issued'          => $this->total_issued,
            'total_used'            => $this->total_used,
        ];
    }
}
