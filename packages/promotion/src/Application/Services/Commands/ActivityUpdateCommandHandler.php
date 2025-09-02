<?php

namespace RedJasmine\Promotion\Application\Services\Commands;

use RedJasmine\Promotion\Application\Services\ActivityApplicationService;
use RedJasmine\Promotion\Domain\Models\Activity;
use RedJasmine\Support\Application\Commands\CommandHandler;
use Throwable;

/**
 * 更新活动命令处理器
 */
class ActivityUpdateCommandHandler extends CommandHandler
{
    public function __construct(
        protected ActivityApplicationService $service
    ) {
    }

    /**
     * @param  ActivityUpdateCommand  $command
     *
     * @return Activity
     * @throws Throwable
     */
    public function handle(ActivityUpdateCommand $command): Activity
    {
        $this->beginDatabaseTransaction();
        
        try {
            // 查找现有活动
            $model = $this->service->repository->find($command->id);
            if (!$model) {
                throw new \RuntimeException('活动不存在');
            }
            
            // 验证活动数据
            $this->service->validateActivityData($command);
            
            // 更新活动模型
            $model = $this->service->transformer->transform($command, $model);
            
            // 保存活动
            $this->service->repository->update($model);
            
            $this->commitDatabaseTransaction();
            return $model;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
}
