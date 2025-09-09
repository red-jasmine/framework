<?php

namespace RedJasmine\Promotion\UI\Http\Admin\Api\Resources;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * 活动订单资源类
 */
class ActivityOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'activity_id' => $this->activity_id,
            'order_id' => $this->order_id,
            'order_sn' => $this->order_sn,
            'user_id' => $this->user_id,
            'user_name' => $this->user_name,
            
            // 商品信息
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'sku_id' => $this->sku_id,
            'sku_name' => $this->sku_name,
            
            // 价格数量信息
            'original_price' => number_format($this->original_price, 2),
            'activity_price' => number_format($this->activity_price, 2),
            'quantity' => $this->quantity,
            'total_amount' => number_format($this->total_amount, 2),
            'discount_amount' => number_format($this->discount_amount, 2),
            
            // 状态信息
            'status' => $this->status,
            'status_label' => match($this->status) {
                'pending' => '待付款',
                'paid' => '已付款',
                'shipped' => '已发货',
                'completed' => '已完成',
                'cancelled' => '已取消',
                'refunded' => '已退款',
                default => '未知'
            },
            'status_color' => match($this->status) {
                'pending' => 'orange',
                'paid' => 'blue',
                'shipped' => 'cyan',
                'completed' => 'green',
                'cancelled' => 'red',
                'refunded' => 'gray',
                default => 'gray'
            },
            
            // 参与信息
            'participated_at' => $this->participated_at?->format('Y-m-d H:i:s'),
            'paid_at' => $this->paid_at?->format('Y-m-d H:i:s'),
            'shipped_at' => $this->shipped_at?->format('Y-m-d H:i:s'),
            'completed_at' => $this->completed_at?->format('Y-m-d H:i:s'),
            
            // 备注信息
            'remark' => $this->remark,
            
            // 时间戳
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            
            // 关联数据（按需加载）
            'activity' => $this->whenLoaded('activity', function () {
                return [
                    'id' => $this->activity->id,
                    'title' => $this->activity->title,
                    'type' => $this->activity->type,
                    'status' => $this->activity->status,
                ];
            }),
        ];
    }
}

