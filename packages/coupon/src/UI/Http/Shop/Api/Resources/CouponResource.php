<?php

namespace RedJasmine\Coupon\UI\Http\Shop\Api\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RedJasmine\Coupon\Domain\Models\Coupon;

/** @mixin Coupon */
class CouponResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'                           => $this->id,
            'owner_type'                   => $this->owner_type,
            'owner_id'                     => $this->owner_id,
            'name'                         => $this->name,
            'description'                  => $this->description,
            'image'                        => $this->image,
            'is_show'                      => $this->is_show,
            'status'                       => $this->status,
            'discount_target'              => $this->discount_target,
            'discount_amount_type'         => $this->discount_amount_type,
            'threshold_type'               => $this->threshold_type,
            'threshold_value'              => $this->threshold_value,
            'discount_amount_value'        => $this->discount_amount_value,
            'max_discount_amount'          => $this->max_discount_amount,
            'validity_type'                => $this->validity_type,
            'validity_start_time'          => $this->validity_start_time,
            'validity_end_time'            => $this->validity_end_time,
            'delayed_effective_time_type'  => $this->delayed_effective_time_type,
            'delayed_effective_time_value' => $this->delayed_effective_time_value,
            'validity_time_type'           => $this->validity_time_type,
            'validity_time_value'          => $this->validity_time_value,
            'usage_rules'                  => $this->usage_rules,
            'receive_rules'                => $this->receive_rules,
            'sort'                         => $this->sort,
            'remarks'                      => $this->remarks,
            'total_quantity'               => $this->total_quantity,
            'total_issued'                 => $this->total_issued,
            'total_used'                   => $this->total_used,
            'usages_count'                 => $this->usages_count,
            'user_coupons_count'           => $this->user_coupons_count,
            'creator'                      => $this->creator,
            'withOperatorAvatar'           => $this->withOperatorAvatar,
            'withOwnerAvatar'              => $this->withOwnerAvatar,
            'withOwnerNickname'            => $this->withOwnerNickname,
            'updater_id'                   => $this->updater_id,
            'uniqueShortId'                => $this->uniqueShortId,
            'creator_id'                   => $this->creator_id,
            'owner'                        => $this->owner,
            'updater'                      => $this->updater,
            'withOperatorNickname'         => $this->withOperatorNickname,
            'updater_type'                 => $this->updater_type,
            'ownerColumn'                  => $this->ownerColumn,
            'creator_type'                 => $this->creator_type,
        ];
    }
}
