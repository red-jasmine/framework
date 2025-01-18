<?php

namespace RedJasmine\Support\Application\CommandHandlers;

use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Support\Data\Data;

/**
 * 删除命令处理器类
 * 继承自CommandHandler，提供删除数据的处理逻辑
 */
class DeleteCommandHandler extends CommandHandler
{

    public function __construct(protected ApplicationCommandService $service)
    {
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
        // 启动数据库事务以确保数据的一致性
        $this->beginDatabaseTransaction();
        try {
            // 根据命令中的ID查找模型
            $model = $this->getRepository()->find($command->getKey());
            // 设置当前模型为待删除模型
            $this->setModel($model);
            // 通过仓库删除模型
            $this->getRepository()->delete($this->model);

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
