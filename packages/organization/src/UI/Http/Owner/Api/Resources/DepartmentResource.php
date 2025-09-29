<?php

namespace RedJasmine\Organization\UI\Http\Owner\Api\Resources;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'org_id' => $this->org_id,
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'sort' => $this->sort,
            'status' => $this->status,
            'status_label' => $this->status->label(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // 关联数据
            'parent' => $this->whenLoaded('parent', function () {
                return new DepartmentResource($this->parent);
            }),

            'children' => $this->whenLoaded('children', function () {
                return DepartmentResource::collection($this->children);
            }),

            'managers' => $this->whenLoaded('managers', function () {
                return MemberResource::collection($this->managers);
            }),

            'members' => $this->whenLoaded('members', function () {
                return MemberResource::collection($this->members);
            }),

            // 扩展信息
            'extension' => $this->whenLoaded('extension', function () {
                return [
                    'members_count' => $this->members()->count(),
                    'children_count' => $this->children()->count(),
                    'managers_count' => $this->managers()->count(),
                ];
            }),
        ];
    }
}
