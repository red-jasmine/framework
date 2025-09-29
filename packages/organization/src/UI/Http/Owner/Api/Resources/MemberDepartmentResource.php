<?php

namespace RedJasmine\Organization\UI\Http\Owner\Api\Resources;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

class MemberDepartmentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'member_id' => $this->member_id,
            'department_id' => $this->department_id,
            'is_primary' => $this->is_primary,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // 关联数据
            'member' => $this->whenLoaded('member', function () {
                return new MemberResource($this->member);
            }),

            'department' => $this->whenLoaded('department', function () {
                return new DepartmentResource($this->department);
            }),
        ];
    }
}
