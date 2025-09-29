<?php

namespace RedJasmine\Organization\UI\Http\Owner\Api\Resources;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

class PositionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'org_id' => $this->org_id,
            'name' => $this->name,
            'code' => $this->code,
            'level' => $this->level,
            'description' => $this->description,
            'sort' => $this->sort,
            'status' => $this->status,
            'status_label' => $this->status->label(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // 关联数据
            'members' => $this->whenLoaded('members', function () {
                return MemberResource::collection($this->members);
            }),

            // 扩展信息
            'extension' => $this->whenLoaded('extension', function () {
                return [
                    'members_count' => $this->members()->count(),
                ];
            }),
        ];
    }
}
