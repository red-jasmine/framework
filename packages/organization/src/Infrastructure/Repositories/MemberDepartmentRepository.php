<?php

namespace RedJasmine\Organization\Infrastructure\Repositories;

use RedJasmine\Organization\Domain\Models\MemberDepartment;
use RedJasmine\Organization\Domain\Repositories\MemberDepartmentRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class MemberDepartmentRepository extends Repository implements MemberDepartmentRepositoryInterface
{
    protected static string $modelClass = MemberDepartment::class;

    /**
     * 查找成员的部门历史
     */
    public function findByMemberId(int $memberId): \Illuminate\Database\Eloquent\Collection
    {
        return MemberDepartment::where('member_id', $memberId)->get();
    }

    /**
     * 查找部门的成员历史
     */
    public function findByDepartmentId(int $departmentId): \Illuminate\Database\Eloquent\Collection
    {
        return MemberDepartment::where('department_id', $departmentId)->get();
    }

    /**
     * 查找成员当前有效的部门关系
     */
    public function findActiveMemberDepartments(int $memberId): \Illuminate\Database\Eloquent\Collection
    {
        return MemberDepartment::where('member_id', $memberId)
            ->whereNull('ended_at')
            ->get();
    }

    /**
     * 查找成员的主部门关系
     */
    public function findPrimaryMemberDepartment(int $memberId): ?MemberDepartment
    {
        return MemberDepartment::where('member_id', $memberId)
            ->where('is_primary', true)
            ->whereNull('ended_at')
            ->first();
    }
}
