<?php

namespace RedJasmine\Project\Application\Services\Commands;

use RedJasmine\Project\Domain\Events\ProjectPaused;
use RedJasmine\Project\Domain\Models\Project;
use RedJasmine\Support\Application\Commands\CommandHandler;

class ProjectPauseCommandHandler extends CommandHandler
{
    public function __construct(
        protected \RedJasmine\Project\Application\Services\ProjectApplicationService $service
    ) {
    }

    public function handle(ProjectPauseCommand $command): Project
    {
        $this->beginDatabaseTransaction();

        try {
            $project = $this->service->find($command->getKey());
            if (!$project) {
                throw new \Exception('项目不存在');
            }

            // 暂停项目
            $project->status = \RedJasmine\Project\Domain\Models\Enums\ProjectStatus::PAUSED;
            $project = $this->service->update($project);

            // 发布项目暂停事件
            event(new ProjectPaused($project, $project->operator));

            $this->commitDatabaseTransaction();
            return $project;
        } catch (\Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
}
