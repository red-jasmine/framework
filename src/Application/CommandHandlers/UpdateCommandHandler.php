<?php

namespace RedJasmine\Support\Application\CommandHandlers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;
use RedJasmine\Support\Facades\ServiceContext;

class UpdateCommandHandler extends CommandHandler
{


    public function handle(Data $command) : ?Model
    {


        // 开始数据库事务
        $this->beginDatabaseTransaction();
        try {
            $this->setModel($this->repository->find($command->id));

            // 对数据进行验证
            $this->validate();
            // 对特殊的模型进行处理，如设置 owner 等

            // 填充模型属性
            $this->fill();

            // 添加操作员信息
            $this->withOperator();

            // 存储模型到仓库
            $this->repository->update($this->model);

            $this->commitDatabaseTransaction();
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
        return $this->model;
    }

    protected function validate() : void
    {

    }

    protected function fill() : void
    {
        $command = $this->command;


        $this->model->fill($command->all());

        if ($this->model instanceof OwnerInterface) {
            $this->model->owner = $command->owner;
        }

    }

    protected function withOperator() : void
    {
        if ($this->model instanceof OperatorInterface) {
            $this->model->creator = ServiceContext::getOperator();
        }


    }


}
