<?php

namespace RedJasmine\Project\Application\Services\Commands;

use RedJasmine\Project\Domain\Events\ProjectRoleCreated;
use RedJasmine\Project\Domain\Models\Project;
use RedJasmine\Project\Domain\Models\ProjectRole;
use RedJasmine\Support\Application\Commands\CommandHandler;

class ProjectCreateRoleCommandHandler extends CommandHandler
{
    public function __construct(
        protected \RedJasmine\Project\Application\Services\ProjectApplicationService $service
    ) {
    }

    public function handle(ProjectCreateRoleCommand $command): ProjectRole
    {
        $this->beginDatabaseTransaction();

        try {
            $project = $this->service->find($command->projectId);
            if (!$project) {
                throw new \Exception('项目不存在');
            }

            // 检查角色代码是否已存在
            if ($this->service->roleRepository->codeExists($command->projectId, $command->code)) {
                throw new \Exception('角色代码已存在');
            }

            // 创建项目角色
            $role = $this->service->createProjectRole($command);

            // 发布角色创建事件
            event(new ProjectRoleCreated($project, $role, $command->creator));

            $this->commitDatabaseTransaction();
            return $role;
        } catch (\Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
}
