<?php

namespace RedJasmine\Project\Application\Services\Commands;

use RedJasmine\Project\Domain\Models\Project;
use RedJasmine\Support\Application\Commands\CommandHandler;

class ProjectUpdateCommandHandler extends CommandHandler
{
    public function __construct(
        protected \RedJasmine\Project\Application\Services\ProjectApplicationService $service
    ) {
    }

    public function handle(ProjectUpdateCommand $command): Project
    {
        $this->beginDatabaseTransaction();

        try {
            // 验证项目代码唯一性
            if ($command->code && $this->service->codeExists($command->owner, $command->code, $command->id)) {
                throw new \Exception('项目代码已存在');
            }

            // 更新项目
            $project = $this->service->update($command);

            $this->commitDatabaseTransaction();
            return $project;
        } catch (\Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
}
