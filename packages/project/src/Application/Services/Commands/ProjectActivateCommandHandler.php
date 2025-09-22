<?php

namespace RedJasmine\Project\Application\Services\Commands;

use RedJasmine\Project\Domain\Events\ProjectActivated;
use RedJasmine\Project\Domain\Models\Project;
use RedJasmine\Support\Application\Commands\CommandHandler;

class ProjectActivateCommandHandler extends CommandHandler
{
    public function __construct(
        protected \RedJasmine\Project\Application\Services\ProjectApplicationService $service
    ) {
    }

    public function handle(ProjectActivateCommand $command): Project
    {
        $this->beginDatabaseTransaction();

        try {
            $project = $this->service->find($command->getKey());
            if (!$project) {
                throw new \Exception('项目不存在');
            }

            // 激活项目
            $project->status = \RedJasmine\Project\Domain\Models\Enums\ProjectStatus::ACTIVE;
            $project = $this->service->update($project);

            // 发布项目激活事件
            event(new ProjectActivated($project, $project->operator));

            $this->commitDatabaseTransaction();
            return $project;
        } catch (\Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
}
