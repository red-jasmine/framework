<?php

namespace RedJasmine\Project\Application\Services\Commands;

use RedJasmine\Project\Domain\Events\MemberJoined;
use RedJasmine\Project\Domain\Models\Project;
use RedJasmine\Project\Domain\Models\ProjectMember;
use RedJasmine\Support\Application\Commands\CommandHandler;

class ProjectAddMemberCommandHandler extends CommandHandler
{
    public function __construct(
        protected \RedJasmine\Project\Application\Services\ProjectApplicationService $service
    ) {
    }

    public function handle(ProjectAddMemberCommand $command): ProjectMember
    {
        $this->beginDatabaseTransaction();

        try {
            $project = $this->service->find($command->projectId);
            if (!$project) {
                throw new \Exception('项目不存在');
            }

            // 检查成员是否已存在
            if ($this->service->memberRepository->isMember($command->projectId, $command->member)) {
                throw new \Exception('成员已存在');
            }

            // 添加项目成员
            $member = $this->service->addProjectMember($command);

            // 发布成员加入事件
            event(new MemberJoined($project, $member, $command->operator));

            $this->commitDatabaseTransaction();
            return $member;
        } catch (\Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
}
