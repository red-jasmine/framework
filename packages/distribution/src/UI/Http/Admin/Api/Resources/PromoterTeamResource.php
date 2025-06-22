<?php

namespace RedJasmine\Distribution\UI\Http\Admin\Api\Resources;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

class PromoterTeamResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'sort' => $this->sort,
            'status' => $this->status,
            'leader_id' => $this->leaderId,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // 关联数据
            'promoters' => $this->whenLoaded('promoters'),
            'promoters_count' => $this->whenCounted('promoters'),
        ];
    }
}