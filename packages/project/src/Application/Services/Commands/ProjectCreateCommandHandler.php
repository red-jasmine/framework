<?php

namespace RedJasmine\Project\Application\Services\Commands;

use RedJasmine\Project\Domain\Events\ProjectCreated;
use RedJasmine\Project\Domain\Models\Project;
use RedJasmine\Support\Application\Commands\CommandHandler;
use RedJasmine\Support\Contracts\UserInterface;

class ProjectCreateCommandHandler extends CommandHandler
{
    public function __construct(
        protected \RedJasmine\Project\Application\Services\ProjectApplicationService $service
    ) {
    }

    public function handle(ProjectCreateCommand $command): Project
    {
        $this->beginDatabaseTransaction();

        try {
            // 验证项目代码唯一性
            if ($command->code && $this->service->codeExists($command->owner, $command->code)) {
                throw new \Exception('项目代码已存在');
            }

            // 创建项目
            $project = $this->service->createProject($command);

            // 发布项目创建事件
            event(new ProjectCreated($project, $command->creator));

            $this->commitDatabaseTransaction();
            return $project;
        } catch (\Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
}
