<?php

namespace RedJasmine\Organization\Infrastructure\Repositories;

use RedJasmine\Organization\Domain\Models\Member;
use RedJasmine\Organization\Domain\Repositories\MemberRepositoryInterface;
use RedJasmine\UserCore\Infrastructure\Repositories\BaseUserRepository;

class MemberRepository extends BaseUserRepository implements MemberRepositoryInterface
{
    protected static string $modelClass = Member::class;

    /**
     * 根据账号名称和组织ID查找成员
     */
    public function findByNameAndOrgId(string $name, int $orgId): ?Member
    {
        return Member::where('name', $name)
            ->where('org_id', $orgId)
            ->first();
    }

    /**
     * 根据组织ID查找活跃成员
     */
    public function findActiveByOrgId(int $orgId): \Illuminate\Database\Eloquent\Collection
    {
        return Member::where('org_id', $orgId)
            ->where('status', \RedJasmine\UserCore\Domain\Enums\UserStatusEnum::ACTIVATED)
            ->get();
    }

    /**
     * 根据部门ID查找成员
     */
    public function findByDepartmentId(int $departmentId): \Illuminate\Database\Eloquent\Collection
    {
        return Member::whereHas('departments', function ($query) use ($departmentId) {
            $query->where('department_id', $departmentId);
        })->get();
    }
}
