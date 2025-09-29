<?php

namespace RedJasmine\Organization\UI\Http\Owner\Api\Resources;

use RedJasmine\Organization\Domain\Models\Department;
use RedJasmine\Organization\Domain\Models\Position;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'org_id' => $this->org_id,
            'member_no' => $this->member_no,
            'name' => $this->name,
            'nickname' => $this->nickname,
            'avatar' => $this->avatar,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'gender' => $this->gender,
            'telephone' => $this->telephone,
            'hired_at' => $this->hired_at,
            'resigned_at' => $this->resigned_at,
            'status' => $this->status,
            'status_label' => $this->status->label(),
            'position_name' => $this->position_name,
            'position_level' => $this->position_level,
            'main_department_id' => $this->main_department_id,
            'departments' => $this->departments,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // 关联数据
            'position' => $this->whenLoaded('position', function () {
                return new PositionResource($this->position);
            }),

            'primary_department' => $this->whenLoaded('primaryDepartment', function () {
                return new DepartmentResource($this->primaryDepartment);
            }),

            'departments_detail' => $this->whenLoaded('departments', function () {
                return DepartmentResource::collection($this->departments);
            }),

            'leader' => $this->whenLoaded('leader', function () {
                return new MemberResource($this->leader);
            }),

            'subordinates' => $this->whenLoaded('subordinates', function () {
                return MemberResource::collection($this->subordinates);
            }),

            'managed_departments' => $this->whenLoaded('managedDepartments', function () {
                return DepartmentResource::collection($this->managedDepartments);
            }),

            // 扩展信息
            'extension' => $this->whenLoaded('extension', function () {
                return [
                    'is_manager' => $this->managedDepartments()->exists(),
                    'is_primary_manager' => $this->managedDepartments()
                        ->wherePivot('is_primary', true)
                        ->exists(),
                    'subordinates_count' => $this->subordinates()->count(),
                    'managed_departments_count' => $this->managedDepartments()->count(),
                ];
            }),
        ];
    }
}
