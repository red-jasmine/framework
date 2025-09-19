<?php

namespace RedJasmine\Organization\Infrastructure\Repositories;

use RedJasmine\Organization\Domain\Models\DepartmentManager;
use RedJasmine\Organization\Domain\Repositories\DepartmentManagerRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class DepartmentManagerRepository extends Repository implements DepartmentManagerRepositoryInterface
{
    protected static string $modelClass = DepartmentManager::class;

    /**
     * 查找部门的管理者历史
     */
    public function findByDepartmentId(int $departmentId): \Illuminate\Database\Eloquent\Collection
    {
        return DepartmentManager::where('department_id', $departmentId)->get();
    }

    /**
     * 查找成员的管理部门历史
     */
    public function findByMemberId(int $memberId): \Illuminate\Database\Eloquent\Collection
    {
        return DepartmentManager::where('member_id', $memberId)->get();
    }

    /**
     * 查找部门当前有效的管理者
     */
    public function findActiveDepartmentManagers(int $departmentId): \Illuminate\Database\Eloquent\Collection
    {
        return DepartmentManager::where('department_id', $departmentId)
            ->whereNull('ended_at')
            ->get();
    }

    /**
     * 查找部门的主要负责人
     */
    public function findPrimaryDepartmentManager(int $departmentId): ?DepartmentManager
    {
        return DepartmentManager::where('department_id', $departmentId)
            ->where('is_primary', true)
            ->whereNull('ended_at')
            ->first();
    }
}
