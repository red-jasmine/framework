<?php

namespace RedJasmine\Project\Infrastructure\Services;

use RedJasmine\Project\Domain\Models\Project;
use RedJasmine\Project\Domain\Models\ProjectRole;
use RedJasmine\Project\Domain\Repositories\ProjectRoleRepositoryInterface;

class ProjectRoleInitializer
{
    public function __construct(
        protected ProjectRoleRepositoryInterface $roleRepository
    ) {
    }

    /**
     * 为项目初始化系统角色
     */
    public function initializeSystemRoles(Project $project): void
    {
        $systemRoles = config('project.system_roles', []);

        foreach ($systemRoles as $roleConfig) {
            $this->createSystemRole($project, $roleConfig);
        }
    }

    /**
     * 创建系统角色
     */
    protected function createSystemRole(Project $project, array $roleConfig): ProjectRole
    {
        $role = new ProjectRole([
            'project_id' => $project->id,
            'name' => $roleConfig['name'],
            'code' => $roleConfig['code'],
            'description' => $roleConfig['description'],
            'is_system' => true,
            'permissions' => $roleConfig['permissions'] ?? [],
            'sort' => 0,
            'status' => 'active',
        ]);

        return $this->roleRepository->store($role);
    }

    /**
     * 检查项目是否有系统角色
     */
    public function hasSystemRoles(Project $project): bool
    {
        return $this->roleRepository->findRolesByProject($project->id)
            ->where('is_system', true)
            ->isNotEmpty();
    }

    /**
     * 获取项目的系统角色
     */
    public function getSystemRoles(Project $project): \Illuminate\Database\Eloquent\Collection
    {
        return $this->roleRepository->findRolesByProject($project->id)
            ->where('is_system', true);
    }
}
