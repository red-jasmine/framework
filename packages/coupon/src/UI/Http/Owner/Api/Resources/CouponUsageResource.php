<?php

declare(strict_types = 1);

namespace RedJasmine\Coupon\UI\Http\Owner\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Coupon\Domain\Models\CouponUsage;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * 优惠券使用记录资源类
 *
 * @mixin CouponUsage
 */
class CouponUsageResource extends JsonResource
{
    /**
     * 将资源转换为数组
     *
     * @param  Request  $request
     *
     * @return array
     */
    public function toArray(Request $request) : array
    {
        return [
            'id'              => $this->id,
            'coupon_id'       => $this->coupon_id,
            'coupon_no'       => $this->coupon_no,
            'user_type'       => $this->user_type,
            'user_id'         => $this->user_id,
            'order_no'        => $this->order_no,
            'discount_amount' => $this->discount_amount,
            'used_at'         => $this->used_at,


        ];
    }
} 