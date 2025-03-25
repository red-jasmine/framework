<?php

namespace RedJasmine\Support\Application\Commands;

use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Application\CommandHandlers\Throwable;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\Support\Data\Data;

/**
 * 删除命令处理器类
 * 继承自CommandHandler，提供删除数据的处理逻辑
 */
class DeleteCommandHandler extends CommandHandler
{

    public function __construct(protected ApplicationService $service)
    {
        $this->context = new HandleContext();
    }


    /**
     * 处理删除命令
     * 该方法通过数据库事务安全地删除指定的数据
     *
     * @param  Data  $command  包含要删除数据的ID的数据对象
     *
     * @throws Throwable 如果删除过程中发生错误，则抛出异常
     */
    public function handle(Data $command) : void
    {
        $this->context->setCommand($command);
        // 启动数据库事务以确保数据的一致性
        $this->beginDatabaseTransaction();
        try {
            // 根据命令中的ID查找模型
            $model = $this->service->repository->find($command->getKey());
            // 通过仓库删除模型
            $this->context->setModel($model);

            // 删除模型
            $this->service->repository->delete($this->context->getModel());
            // 提交数据库事务
            $this->commitDatabaseTransaction();
        } catch (Throwable $throwable) {
            // 如果发生异常，回滚数据库事务
            $this->rollBackDatabaseTransaction();
            // 重新抛出异常以通知调用者
            throw $throwable;
        }
    }

}
