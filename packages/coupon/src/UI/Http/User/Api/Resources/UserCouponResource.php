<?php

namespace RedJasmine\Coupon\UI\Http\User\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserCouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'owner' => $this->owner,
            'operator' => $this->operator,
            'coupon_id' => $this->coupon_id,
            'user' => $this->user,
            'status' => $this->status,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'used_at' => $this->used_at,
            'expired_at' => $this->expired_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // 关联数据
            'coupon' => $this->whenLoaded('coupon'),
            'usage' => $this->whenLoaded('usage'),
        ];
    }
}