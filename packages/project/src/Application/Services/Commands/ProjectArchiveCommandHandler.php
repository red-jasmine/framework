<?php

namespace RedJasmine\Project\Application\Services\Commands;

use RedJasmine\Project\Domain\Events\ProjectArchived;
use RedJasmine\Project\Domain\Models\Project;
use RedJasmine\Support\Application\Commands\CommandHandler;

class ProjectArchiveCommandHandler extends CommandHandler
{
    public function __construct(
        protected \RedJasmine\Project\Application\Services\ProjectApplicationService $service
    ) {
    }

    public function handle(ProjectArchiveCommand $command): Project
    {
        $this->beginDatabaseTransaction();

        try {
            $project = $this->service->find($command->getKey());
            if (!$project) {
                throw new \Exception('项目不存在');
            }

            // 归档项目
            $project->status = \RedJasmine\Project\Domain\Models\Enums\ProjectStatus::ARCHIVED;
            $project = $this->service->update($project);

            // 发布项目归档事件
            event(new ProjectArchived($project, $project->operator));

            $this->commitDatabaseTransaction();
            return $project;
        } catch (\Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
}
