<?php

namespace RedJasmine\Coupon\UI\Http\Admin\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'owner' => $this->owner,
            'operator' => $this->operator,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'status' => $this->status,
            'discount_type' => $this->discount_type,
            'discount_value' => $this->discount_value,
            'max_discount_amount' => $this->max_discount_amount,
            'is_ladder' => $this->is_ladder,
            'ladder_rules' => $this->ladder_rules,
            'threshold_type' => $this->threshold_type,
            'threshold_value' => $this->threshold_value,
            'is_threshold_required' => $this->is_threshold_required,
            'validity_type' => $this->validity_type,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'relative_days' => $this->relative_days,
            'max_usage_per_user' => $this->max_usage_per_user,
            'max_usage_total' => $this->max_usage_total,
            'usage_rules' => $this->usage_rules,
            'collect_rules' => $this->collect_rules,
            'cost_bearer_type' => $this->cost_bearer_type,
            'cost_bearer_id' => $this->cost_bearer_id,
            'cost_bearer_name' => $this->cost_bearer_name,
            'issue_strategy' => $this->issue_strategy,
            'total_issue_limit' => $this->total_issue_limit,
            'current_issue_count' => $this->current_issue_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // 关联数据
            'issueStat' => $this->whenLoaded('issueStat'),
            'userCoupons' => $this->whenLoaded('userCoupons'),
        ];
    }
}