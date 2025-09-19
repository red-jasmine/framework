<?php

namespace RedJasmine\Organization\Infrastructure\Repositories;

use RedJasmine\Organization\Domain\Models\Member;
use RedJasmine\Organization\Domain\Repositories\MemberRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class MemberRepository extends Repository implements MemberRepositoryInterface
{
    protected static string $modelClass = Member::class;

    /**
     * 根据成员编号查找
     */
    public function findByMemberNo(string $memberNo): ?Member
    {
        return Member::where('member_no', $memberNo)->first();
    }

    /**
     * 根据组织ID查找成员
     */
    public function findByOrgId(int $orgId): \Illuminate\Database\Eloquent\Collection
    {
        return Member::where('org_id', $orgId)->get();
    }

    /**
     * 根据部门ID查找成员
     */
    public function findByDepartmentId(int $departmentId): \Illuminate\Database\Eloquent\Collection
    {
        return Member::where('main_department_id', $departmentId)
            ->orWhereJsonContains('departments', $departmentId)
            ->get();
    }

    /**
     * 根据手机号查找
     */
    public function findByMobile(string $mobile): ?Member
    {
        return Member::where('mobile', $mobile)->first();
    }

    /**
     * 根据邮箱查找
     */
    public function findByEmail(string $email): ?Member
    {
        return Member::where('email', $email)->first();
    }
}
