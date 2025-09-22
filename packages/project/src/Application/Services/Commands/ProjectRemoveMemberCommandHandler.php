<?php

namespace RedJasmine\Project\Application\Services\Commands;

use RedJasmine\Project\Domain\Events\MemberLeft;
use RedJasmine\Project\Domain\Models\Project;
use RedJasmine\Project\Domain\Models\ProjectMember;
use RedJasmine\Support\Application\Commands\CommandHandler;

class ProjectRemoveMemberCommandHandler extends CommandHandler
{
    public function __construct(
        protected \RedJasmine\Project\Application\Services\ProjectApplicationService $service
    ) {
    }

    public function handle(ProjectRemoveMemberCommand $command): bool
    {
        $this->beginDatabaseTransaction();

        try {
            $member = $this->service->memberRepository->find($command->getKey());
            if (!$member) {
                throw new \Exception('项目成员不存在');
            }

            $project = $this->service->find($member->project_id);
            if (!$project) {
                throw new \Exception('项目不存在');
            }

            // 移除项目成员
            $result = $this->service->removeProjectMember($command->getKey());

            if ($result) {
                // 发布成员离开事件
                event(new MemberLeft($project, $member, $member->operator));
            }

            $this->commitDatabaseTransaction();
            return $result;
        } catch (\Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
}
