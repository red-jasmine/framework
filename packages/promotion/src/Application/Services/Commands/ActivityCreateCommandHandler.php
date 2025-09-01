<?php

namespace RedJasmine\Promotion\Application\Services\Commands;

use RedJasmine\Promotion\Application\Services\ActivityApplicationService;
use RedJasmine\Promotion\Domain\Models\Activity;
use RedJasmine\Support\Application\Commands\CommandHandler;
use Throwable;

/**
 * 创建活动命令处理器
 */
class ActivityCreateCommandHandler extends CommandHandler
{
    public function __construct(
        protected ActivityApplicationService $service
    ) {
    }

    public function handle(ActivityCreateCommand $command): Activity
    {
        $this->beginDatabaseTransaction();
        
        try {
            // 验证活动数据
            $this->service->validateActivityData($command);
            
            // 创建活动模型
            $model = $this->service->newModel();
            $model = $this->service->transformer->transform($command, $model);
            
            // 存储活动
            $this->service->repository->store($model);
            
            $this->commitDatabaseTransaction();
            return $model;
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
    }
}
