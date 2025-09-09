<?php

namespace RedJasmine\Promotion\UI\Http\Admin\Api\Resources;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * 活动SKU资源类
 */
class ActivitySkuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'activity_id' => $this->activity_id,
            'activity_product_id' => $this->activity_product_id,
            'sku_id' => $this->sku_id,
            'sku_name' => $this->sku_name,
            
            // 价格信息
            'original_price' => number_format($this->original_price, 2),
            'activity_price' => number_format($this->activity_price, 2),
            'discount_amount' => number_format($this->original_price - $this->activity_price, 2),
            'discount_rate' => $this->original_price > 0 ? 
                round((($this->original_price - $this->activity_price) / $this->original_price) * 100, 2) : 0,
            
            // 库存信息
            'activity_stock' => $this->activity_stock,
            'sold_quantity' => $this->sold_quantity ?? 0,
            'remaining_stock' => $this->activity_stock ? 
                max(0, $this->activity_stock - ($this->sold_quantity ?? 0)) : null,
            
            // 限购信息
            'limit_quantity' => $this->limit_quantity,
            
            // 状态信息
            'status' => $this->status,
            'status_label' => match($this->status) {
                'active' => '正常',
                'inactive' => '停用',
                'sold_out' => '售罄',
                default => '未知'
            },
            'status_color' => match($this->status) {
                'active' => 'green',
                'inactive' => 'gray',
                'sold_out' => 'red',
                default => 'gray'
            },
            'is_show' => $this->is_show,
            
            // 统计信息
            'total_orders' => $this->total_orders ?? 0,
            'total_sales' => number_format($this->total_sales ?? 0, 2),
            
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
            
            'product' => $this->whenLoaded('product', function () {
                return [
                    'id' => $this->product->id,
                    'product_id' => $this->product->product_id,
                    'product_name' => $this->product->product_name,
                    'product_image' => $this->product->product_image,
                ];
            }),
        ];
    }
}

