<?php

namespace RedJasmine\Organization\Domain\Repositories;

use RedJasmine\UserCore\Domain\Repositories\BaseUserRepositoryInterface;

interface MemberRepositoryInterface extends BaseUserRepositoryInterface
{
    /**
     * 根据账号名称和组织ID查找成员
     */
    public function findByNameAndOrgId(string $name, int $orgId): ?\RedJasmine\Organization\Domain\Models\Member;

    /**
     * 根据组织ID查找活跃成员
     */
    public function findActiveByOrgId(int $orgId): \Illuminate\Database\Eloquent\Collection;

    /**
     * 根据部门ID查找成员
     */
    public function findByDepartmentId(int $departmentId): \Illuminate\Database\Eloquent\Collection;
}


