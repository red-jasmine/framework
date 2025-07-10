<?php

declare(strict_types=1);

namespace RedJasmine\Coupon\UI\Http\Shop\Api\Resources;

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
            'order_no' => $this->order_no,
            'threshold_amount' => $this->threshold_amount,
            'discount_amount' => $this->discount_amount,
            'final_discount_amount' => $this->final_discount_amount,
            'used_at' => $this->used_at,
            'cost_bearer_type' => $this->cost_bearer_type,
            'cost_bearer_id' => $this->cost_bearer_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // 计算字段
            'discount_ratio' => $this->getDiscountRatio(),
            'saved_amount' => $this->getSavedAmount(),
            'usage_summary' => $this->getUsageSummary(),

            // 成本承担方信息
            'cost_bearer_info' => $this->getCostBearerInfo(),

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
                    'discount_target' => $this->coupon->discount_target,
                    'discount_amount_type' => $this->coupon->discount_amount_type,
                ];
            }),

            // 订单相关信息
            'order_info' => [
                'order_no' => $this->order_no,
                'threshold_amount' => $this->threshold_amount,
                'discount_amount' => $this->discount_amount,
                'final_discount_amount' => $this->final_discount_amount,
            ],

            // 使用详情
            'usage_details' => [
                'used_at' => $this->used_at,
                'discount_ratio' => $this->getDiscountRatio(),
                'saved_amount' => $this->getSavedAmount(),
                'usage_summary' => $this->getUsageSummary(),
            ],

            // 时间信息
            'time_info' => [
                'used_at' => $this->used_at,
                'used_date' => $this->used_at->toDateString(),
                'used_time' => $this->used_at->toTimeString(),
                'used_timestamp' => $this->used_at->timestamp,
                'used_human' => $this->used_at->diffForHumans(),
            ],
        ];
    }
} 