<?php

namespace RedJasmine\Project\Application\Services\Commands;

use RedJasmine\Project\Domain\Events\ProjectRoleDeleted;
use RedJasmine\Project\Domain\Models\Project;
use RedJasmine\Project\Domain\Models\ProjectRole;
use RedJasmine\Support\Application\Commands\CommandHandler;

class ProjectDeleteRoleCommandHandler extends CommandHandler
{
    public function __construct(
        protected \RedJasmine\Project\Application\Services\ProjectApplicationService $service
    ) {
    }

    public function handle(ProjectDeleteRoleCommand $command): bool
    {
        $this->beginDatabaseTransaction();

        try {
            $role = $this->service->roleRepository->find($command->getKey());
            if (!$role) {
                throw new \Exception('项目角色不存在');
            }

            $project = $this->service->find($role->project_id);
            if (!$project) {
                throw new \Exception('项目不存在');
            }

            // 删除项目角色
            $result = $this->service->deleteProjectRole($command->getKey());

            if ($result) {
                // 发布角色删除事件
                event(new ProjectRoleDeleted($project, $role, $role->operator));
            }

            $this->commitDatabaseTransaction();
            return $result;
        } catch (\Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
}
