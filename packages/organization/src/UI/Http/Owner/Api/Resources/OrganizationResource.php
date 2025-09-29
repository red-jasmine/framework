<?php

namespace RedJasmine\Organization\UI\Http\Owner\Api\Resources;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'owner_id' => $this->owner_id,
            'name' => $this->name,
            'code' => $this->code,
            'type' => $this->type,
            'type_label' => $this->type->label(),
            'description' => $this->description,
            'logo' => $this->logo,
            'website' => $this->website,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'status' => $this->status,
            'status_label' => $this->status->label(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // 关联数据
            'departments' => $this->whenLoaded('departments', function () {
                return DepartmentResource::collection($this->departments);
            }),

            'positions' => $this->whenLoaded('positions', function () {
                return PositionResource::collection($this->positions);
            }),

            'members' => $this->whenLoaded('members', function () {
                return MemberResource::collection($this->members);
            }),

            // 扩展信息
            'extension' => $this->whenLoaded('extension', function () {
                return [
                    'departments_count' => $this->departments()->count(),
                    'positions_count' => $this->positions()->count(),
                    'members_count' => $this->members()->count(),
                ];
            }),
        ];
    }
}
