<?php

namespace RedJasmine\Organization\Infrastructure\Repositories;

use RedJasmine\Organization\Domain\Models\Department;
use RedJasmine\Organization\Domain\Repositories\DepartmentRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class DepartmentRepository extends Repository implements DepartmentRepositoryInterface
{
    protected static string $modelClass = Department::class;

    /**
     * 根据组织ID查找部门
     */
    public function findByOrgId(int $orgId): \Illuminate\Database\Eloquent\Collection
    {
        return Department::where('org_id', $orgId)->get();
    }

    /**
     * 查找子部门
     */
    public function findChildren(int $parentId): \Illuminate\Database\Eloquent\Collection
    {
        return Department::where('parent_id', $parentId)->get();
    }
}
