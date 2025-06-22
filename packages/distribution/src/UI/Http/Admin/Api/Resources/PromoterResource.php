<?php

namespace RedJasmine\Distribution\UI\Http\Admin\Api\Resources;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

class PromoterResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'owner_type' => $this->owner_type,
            'owner_id' => $this->owner_id,
            'parent_id' => $this->parent_id,
            'group_id' => $this->group_id,
            'team_id' => $this->team_id,
            'level' => $this->level,
            'status' => $this->status,
            'mobile' => $this->mobile,
            'nickname' => $this->nickname,
            'avatar' => $this->avatar,
            'real_name' => $this->real_name,
            'id_card' => $this->id_card,
            'commission_rate' => $this->commission_rate,
            'parent_commission_rate' => $this->parent_commission_rate,
            'total_commission' => $this->total_commission,
            'available_commission' => $this->available_commission,
            'frozen_commission' => $this->frozen_commission,
            'total_order_amount' => $this->total_order_amount,
            'total_order_count' => $this->total_order_count,
            'direct_promoter_count' => $this->direct_promoter_count,
            'total_promoter_count' => $this->total_promoter_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // 关联数据
            'parent' => $this->whenLoaded('parent'),
            'group' => $this->whenLoaded('group'),
            'team' => $this->whenLoaded('team'),
            'level_info' => $this->whenLoaded('level'),
        ];
    }
}