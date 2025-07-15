<?php

declare(strict_types=1);

namespace RedJasmine\Coupon\UI\Http\Shop\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Coupon\Domain\Models\UserCoupon;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * 用户优惠券资源类
 * 
 * @mixin UserCoupon
 */
class UserCouponResource extends JsonResource
{
    /**
     * 将资源转换为数组
     * 
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'coupon_id' => $this->coupon_id,
            'coupon_no' => $this->coupon_no,
            'user_type' => $this->user_type,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'status_text' => $this->getStatusText(),
            'issue_time' => $this->issue_time,
            'expire_time' => $this->expire_time,
            'used_time' => $this->used_time,
            'order_id' => $this->order_id,
            'is_available' => $this->isAvailable(),
            'is_expired' => $this->isExpired(),
            'is_used' => $this->isUsed(),
            'remaining_days' => $this->getRemainingDays(),
            'remaining_hours' => $this->getRemainingHours(),
            'display_name' => $this->getDisplayName(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // 关联数据
            'coupon' => $this->whenLoaded('coupon', function () {
                return new CouponResource($this->coupon);
            }),

            // 条件字段
            'coupon_info' => $this->when($this->coupon, function () {
                return [
                    'name' => $this->coupon->name,
                    'description' => $this->coupon->description,
                    'image' => $this->coupon->image,
                    'discount_level' => $this->coupon->discount_level,
                    'discount_amount_type' => $this->coupon->discount_amount_type,
                    'discount_amount_value' => $this->coupon->discount_amount_value,
                    'threshold_type' => $this->coupon->threshold_type,
                    'threshold_value' => $this->coupon->threshold_value,
                    'max_discount_amount' => $this->coupon->max_discount_amount,
                    'usage_rules' => $this->coupon->usage_rules,
                ];
            }),

            // 使用信息
            'usage_info' => $this->when($this->isUsed(), function () {
                return [
                    'used_time' => $this->used_time,
                    'order_id' => $this->order_id,
                ];
            }),

            // 过期信息
            'expire_info' => $this->when(!$this->isExpired(), function () {
                return [
                    'expire_time' => $this->expire_time,
                    'remaining_days' => $this->getRemainingDays(),
                    'remaining_hours' => $this->getRemainingHours(),
                ];
            }),

            // 可用性信息
            'availability' => [
                'is_available' => $this->isAvailable(),
                'is_expired' => $this->isExpired(),
                'is_used' => $this->isUsed(),
                'status_text' => $this->getStatusText(),
            ],
        ];
    }
} 