<?php

namespace RedJasmine\Project\Application\Services;

use RedJasmine\Project\Domain\Contracts\ProjectPermissionProviderInterface;
use RedJasmine\Project\Domain\Data\ProjectData;
use RedJasmine\Project\Domain\Data\ProjectMemberData;
use RedJasmine\Project\Domain\Data\ProjectRoleData;
use RedJasmine\Project\Domain\Models\Project;
use RedJasmine\Project\Domain\Models\ProjectMember;
use RedJasmine\Project\Domain\Models\ProjectRole;
use RedJasmine\Project\Domain\Repositories\ProjectMemberRepositoryInterface;
use RedJasmine\Project\Domain\Repositories\ProjectRepositoryInterface;
use RedJasmine\Project\Domain\Repositories\ProjectRoleRepositoryInterface;
use RedJasmine\Project\Domain\Transformers\ProjectTransformer;
use RedJasmine\Project\Infrastructure\Helpers\ProjectCodeGenerator;
use RedJasmine\Project\Infrastructure\Services\ProjectRoleInitializer;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Domain\Contracts\UserInterface;

class ProjectApplicationService extends ApplicationService
{
    public static string $hookNamePrefix = 'project.application';
    protected static string $modelClass = Project::class;

    public function __construct(
        public ProjectRepositoryInterface $repository,
        public ProjectTransformer $transformer,
        public ProjectMemberRepositoryInterface $memberRepository,
        public ProjectRoleRepositoryInterface $roleRepository,
        public ProjectPermissionProviderInterface $permissionProvider,
        public ProjectCodeGenerator $codeGenerator,
        public ProjectRoleInitializer $roleInitializer
    ) {
    }

    protected static $macros = [
        'create' => \RedJasmine\Project\Application\Services\Commands\ProjectCreateCommandHandler::class,
        'update' => \RedJasmine\Project\Application\Services\Commands\ProjectUpdateCommandHandler::class,
        'activate' => \RedJasmine\Project\Application\Services\Commands\ProjectActivateCommandHandler::class,
        'pause' => \RedJasmine\Project\Application\Services\Commands\ProjectPauseCommandHandler::class,
        'archive' => \RedJasmine\Project\Application\Services\Commands\ProjectArchiveCommandHandler::class,
        'addMember' => \RedJasmine\Project\Application\Services\Commands\ProjectAddMemberCommandHandler::class,
        'removeMember' => \RedJasmine\Project\Application\Services\Commands\ProjectRemoveMemberCommandHandler::class,
        'createRole' => \RedJasmine\Project\Application\Services\Commands\ProjectCreateRoleCommandHandler::class,
        'updateRole' => \RedJasmine\Project\Application\Services\Commands\ProjectUpdateRoleCommandHandler::class,
        'deleteRole' => \RedJasmine\Project\Application\Services\Commands\ProjectDeleteRoleCommandHandler::class,
    ];

    /**
     * 创建项目
     */
    public function createProject(ProjectData $data): Project
    {
        // 生成项目代码
        if (empty($data->code)) {
            $data->code = $this->codeGenerator->generate($data->owner, $data->name);
        }

        $project = $this->create($data);

        // 初始化系统角色
        $this->roleInitializer->initializeSystemRoles($project);

        return $project;
    }

    /**
     * 添加项目成员
     */
    public function addProjectMember(ProjectMemberData $data): ProjectMember
    {
        $member = new \RedJasmine\Project\Domain\Models\ProjectMember();
        $transformer = new \RedJasmine\Project\Domain\Transformers\ProjectMemberTransformer();
        $member = $transformer->transform($data, $member);
        $this->memberRepository->store($member);

        return $member;
    }

    /**
     * 移除项目成员
     */
    public function removeProjectMember(string $memberId): bool
    {
        $member = $this->memberRepository->find($memberId);
        if (!$member) {
            return false;
        }

        return $this->memberRepository->delete($member);
    }

    /**
     * 创建项目角色
     */
    public function createProjectRole(ProjectRoleData $data): ProjectRole
    {
        $role = new \RedJasmine\Project\Domain\Models\ProjectRole();
        $transformer = new \RedJasmine\Project\Domain\Transformers\ProjectRoleTransformer();
        $role = $transformer->transform($data, $role);
        $this->roleRepository->store($role);

        return $role;
    }

    /**
     * 更新项目角色
     */
    public function updateProjectRole(ProjectRoleData $data): ProjectRole
    {
        $role = $this->roleRepository->find($data->id);
        if (!$role) {
            throw new \Exception('项目角色不存在');
        }

        $role = $this->roleRepository->transformer->transform($data, $role);
        $this->roleRepository->update($role);

        return $role;
    }

    /**
     * 删除项目角色
     */
    public function deleteProjectRole(string $roleId): bool
    {
        $role = $this->roleRepository->find($roleId);
        if (!$role) {
            return false;
        }

        if ($role->is_system) {
            throw new \Exception('系统角色不能删除');
        }

        return $this->roleRepository->delete($role);
    }

    /**
     * 检查用户权限
     */
    public function hasPermission(Project $project, UserInterface $member, string $permission): bool
    {
        return $this->permissionProvider->hasPermission($project, $member, $permission);
    }

    /**
     * 获取项目成员
     */
    public function getProjectMembers(string $projectId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->memberRepository->findMembersByProject($projectId);
    }

    /**
     * 获取项目角色
     */
    public function getProjectRoles(string $projectId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->roleRepository->findRolesByProject($projectId);
    }

    /**
     * 检查项目代码是否已存在
     */
    public function codeExists(UserInterface $owner, string $code, ?string $excludeId = null): bool
    {
        return $this->repository->codeExists($owner, $code, $excludeId);
    }
}
