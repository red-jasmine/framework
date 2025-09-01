<?php

namespace RedJasmine\Promotion\Application\Services\Commands;

use RedJasmine\Promotion\Application\Services\ActivityApplicationService;
use RedJasmine\Promotion\Domain\Models\ActivityOrder;
use RedJasmine\Support\Application\Commands\CommandHandler;
use Throwable;

/**
 * 参与活动命令处理器
 */
class ActivityParticipateCommandHandler extends CommandHandler
{
    public function __construct(
        protected ActivityApplicationService $service
    ) {
    }

    public function handle(ActivityParticipateCommand $command): ActivityOrder
    {
        $this->beginDatabaseTransaction();
        
        try {
            // 查找活动
            $activity = $this->service->repository->find($command->activityId);
            if (!$activity) {
                throw new \RuntimeException('活动不存在');
            }
            
            // 处理用户参与
            $activityOrder = $this->service->handleParticipation(
                $activity,
                $command->user,
                $command->participationData
            );
            
            $this->commitDatabaseTransaction();
            return $activityOrder;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
}
