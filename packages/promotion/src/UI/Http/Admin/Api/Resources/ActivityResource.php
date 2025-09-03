<?php

namespace RedJasmine\Promotion\UI\Http\Admin\Api\Resources;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * 活动资源类
 */
class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'type_label' => $this->type->label(),
            'status' => $this->status,
            'status_label' => $this->status->label(),
            'status_color' => $this->status->color(),
            'owner_type' => $this->owner_type,
            'owner_id' => $this->owner_id,
            'client_type' => $this->client_type,
            'client_id' => $this->client_id,
            
            // 时间信息
            'sign_up_start_time' => $this->sign_up_start_time?->format('Y-m-d H:i:s'),
            'sign_up_end_time' => $this->sign_up_end_time?->format('Y-m-d H:i:s'),
            'start_time' => $this->start_time?->format('Y-m-d H:i:s'),
            'end_time' => $this->end_time?->format('Y-m-d H:i:s'),
            
            // 活动配置
            'product_requirements' => $this->product_requirements,
            'shop_requirements' => $this->shop_requirements,
            'user_requirements' => $this->user_requirements,
            'rules' => $this->rules,
            'overlay_rules' => $this->overlay_rules,
            
            // 显示状态
            'is_show' => $this->is_show,
            
            // 统计信息
            'total_products' => $this->total_products,
            'total_orders' => $this->total_orders,
            'total_sales' => $this->total_sales,
            'total_participants' => $this->total_participants,
            
            // 计算字段
            'participation_rate' => $this->total_participants > 0 ? 
                round(($this->total_orders / $this->total_participants) * 100, 2) : 0,
            'average_order_value' => $this->total_orders > 0 ? 
                round($this->total_sales / $this->total_orders, 2) : 0,
            
            // 状态判断
            'can_participate' => $this->canParticipate(),
            'is_in_sign_up_period' => $this->isInSignUpPeriod(),
            'has_started' => $this->hasStarted(),
            'has_ended' => $this->hasEnded(),
            
            // 操作员信息
            'creator_type' => $this->creator_type,
            'creator_id' => $this->creator_id,
            'updater_type' => $this->updater_type,
            'updater_id' => $this->updater_id,
            
            // 时间戳
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s'),
            
            // 关联数据（按需加载）
            'products' => $this->whenLoaded('products', function () {
                return ActivityProductResource::collection($this->products);
            }),
            
            'participations' => $this->whenLoaded('participations', function () {
                return ActivityOrderResource::collection($this->participations);
            }),
        ];
    }
}
