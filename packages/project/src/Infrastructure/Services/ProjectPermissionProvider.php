<?php

namespace RedJasmine\Project\Infrastructure\Services;

use RedJasmine\Project\Domain\Contracts\ProjectPermissionProviderInterface;
use RedJasmine\Project\Domain\Models\Project;
use RedJasmine\Project\Domain\Models\ProjectRole;
use RedJasmine\Project\Domain\Repositories\ProjectMemberRepositoryInterface;
use RedJasmine\Project\Domain\Repositories\ProjectRoleRepositoryInterface;

class ProjectPermissionProvider implements ProjectPermissionProviderInterface
{
    public function __construct(
        protected ProjectMemberRepositoryInterface $memberRepository,
        protected ProjectRoleRepositoryInterface $roleRepository
    ) {
    }

    public function hasPermission(Project $project, $member, string $permission): bool
    {
        $projectMember = $this->memberRepository->findByProjectAndMember($project->id, $member);

        if (!$projectMember) {
            return false;
        }

        return $projectMember->hasPermission($permission);
    }

    public function getPermissions(Project $project, $member): array
    {
        $projectMember = $this->memberRepository->findByProjectAndMember($project->id, $member);

        if (!$projectMember) {
            return [];
        }

        return $projectMember->permissions ?? [];
    }

    public function getRolePermissions(ProjectRole $role): array
    {
        return $role->permissions ?? [];
    }

    public function getProjectPermissions(): array
    {
        return config('project.default_permissions', []);
    }

    public function isProjectAdmin(Project $project, $member): bool
    {
        return $this->hasPermission($project, $member, 'project.admin');
    }

    public function isProjectOwner(Project $project, $member): bool
    {
        return $this->hasPermission($project, $member, 'project.owner');
    }
}
