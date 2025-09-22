<?php

namespace RedJasmine\Project\Application\Services\Commands;

use RedJasmine\Project\Domain\Models\ProjectRole;
use RedJasmine\Support\Application\Commands\CommandHandler;

class ProjectUpdateRoleCommandHandler extends CommandHandler
{
    public function __construct(
        protected \RedJasmine\Project\Application\Services\ProjectApplicationService $service
    ) {
    }

    public function handle(ProjectUpdateRoleCommand $command): ProjectRole
    {
        $this->beginDatabaseTransaction();

        try {
            // 检查角色代码是否已存在
            if ($this->service->roleRepository->codeExists($command->projectId, $command->code, $command->id)) {
                throw new \Exception('角色代码已存在');
            }

            // 更新项目角色
            $role = $this->service->updateProjectRole($command);

            $this->commitDatabaseTransaction();
            return $role;
        } catch (\Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
}
