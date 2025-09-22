<?php

namespace RedJasmine\Project\Domain\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Project\Domain\Models\ProjectRole;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

interface ProjectRoleRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据项目和代码查找角色
     */
    public function findByProjectAndCode(string $projectId, string $code): ?ProjectRole;

    /**
     * 查找项目的所有角色
     */
    public function findRolesByProject(string $projectId): Collection;

    /**
     * 查找系统角色
     */
    public function findSystemRoles(): Collection;

    /**
     * 根据状态查找项目角色
     */
    public function findRolesByStatus(string $projectId, string $status): Collection;

    /**
     * 检查角色代码是否已存在
     */
    public function codeExists(string $projectId, string $code, ?string $excludeId = null): bool;

    /**
     * 获取项目的角色数量
     */
    public function getRolesCount(string $projectId): int;
}
