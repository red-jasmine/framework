<?php

namespace RedJasmine\Coupon\UI\Http\User\Api\Resources;

use Illuminate\Http\Request;

use RedJasmine\Coupon\Domain\Models\UserCoupon;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @mixin UserCoupon
 */
class UserCouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     *
     * @return array
     */
    public function toArray(Request $request) : array
    {
        return [
            'id'                  => $this->id,
            'coupon_id'           => $this->coupon_id,
            'coupon_no'           => $this->coupon_no,
            'user'                => $this->user,
            'status'              => $this->status,
            'validity_start_time' => $this->validity_start_time,
            'validity_end_time'   => $this->validity_end_time,
            'issue_time'          => $this->issue_time,
            'used_time'           => $this->used_time,
            'created_at'          => $this->created_at,
            'updated_at'          => $this->updated_at,
            // 关联数据
            'coupon'              => $this->whenLoaded('coupon', function () {
                return CouponResource::make($this->coupon);
            }),

        ];
    }
}