<?php

namespace RedJasmine\Project\Domain\Contracts;

use RedJasmine\Project\Domain\Models\Project;
use RedJasmine\Project\Domain\Models\ProjectRole;

interface ProjectPermissionProviderInterface
{
    /**
     * 检查用户是否有指定权限
     */
    public function hasPermission(Project $project, $member, string $permission): bool;

    /**
     * 获取用户在项目中的所有权限
     */
    public function getPermissions(Project $project, $member): array;

    /**
     * 获取角色的所有权限
     */
    public function getRolePermissions(ProjectRole $role): array;

    /**
     * 获取项目的所有权限列表
     */
    public function getProjectPermissions(): array;

    /**
     * 检查用户是否为项目管理员
     */
    public function isProjectAdmin(Project $project, $member): bool;

    /**
     * 检查用户是否为项目所有者
     */
    public function isProjectOwner(Project $project, $member): bool;
}
