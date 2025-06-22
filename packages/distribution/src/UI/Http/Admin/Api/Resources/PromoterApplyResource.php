<?php

namespace RedJasmine\Distribution\UI\Http\Admin\Api\Resources;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

class PromoterApplyResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'owner_type' => $this->owner_type,
            'owner_id' => $this->owner_id,
            'promoter_id' => $this->promoter_id,
            'type' => $this->type,
            'level' => $this->level,
            'status' => $this->status,
            'mobile' => $this->mobile,
            'nickname' => $this->nickname,
            'avatar' => $this->avatar,
            'real_name' => $this->real_name,
            'id_card' => $this->id_card,
            'remark' => $this->remark,
            'apply_time' => $this->apply_time,
            'audit_time' => $this->audit_time,
            'auditor_id' => $this->auditor_id,
            'auditor_name' => $this->auditor_name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // 关联数据
            'promoter' => $this->whenLoaded('promoter'),
        ];
    }
}