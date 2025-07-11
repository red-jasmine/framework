<?php

declare(strict_types=1);

namespace RedJasmine\Coupon\UI\Http\User\Api\Resources;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'is_show' => $this->is_show,
            'status' => $this->status,
            'discount_target' => $this->discount_target,
            'discount_amount_type' => $this->discount_amount_type,
            'discount_amount_value' => $this->discount_amount_value,
            'threshold_type' => $this->threshold_type,
            'threshold_value' => $this->threshold_value,
            'max_discount_amount' => $this->max_discount_amount,
            'validity_type' => $this->validity_type,
            'validity_start_time' => $this->validity_start_time,
            'validity_end_time' => $this->validity_end_time,
            'delayed_effective_time' => $this->delayed_effective_time,
            'validity_time' => $this->validity_time,
            'usage_rules' => $this->usage_rules,
            'receive_rules' => $this->receive_rules,
            'sort' => $this->sort,
            'total_quantity' => $this->total_quantity,
            'total_issued' => $this->total_issued,
            'total_used' => $this->total_used,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // 计算字段
            'label' => $this->label,
            'remaining_issue_count' => $this->getRemainingIssueCount(),
            'is_issue_limit_reached' => $this->isIssueLimitReached(),
            'can_collect' => $this->canCollect(),
            
            // 关联数据
            'user_coupons' => UserCouponResource::collection($this->whenLoaded('userCoupons')),
            'usages' => $this->whenLoaded('usages'),
            'issue_statistics' => $this->whenLoaded('issueStatistics'),
        ];
    }
} 