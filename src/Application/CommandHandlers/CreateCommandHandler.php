<?php

namespace RedJasmine\Support\Application\CommandHandlers;


use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Facades\ServiceContext;
use Throwable;

class CreateCommandHandler extends CommandHandler
{


    /**
     * 处理命令对象
     *
     * @param  Data  $command  被处理的命令对象
     *
     * @return mixed 返回处理后的模型对象或其他相关结果
     * @throws Throwable
     */
    public function handle(Data $command) : mixed
    {

        // 设置命令对象
        $this->setCommand($command);

        // 创建领域模型
        $this->setModel($this->createModel($command));

        // 开始数据库事务
        $this->beginDatabaseTransaction();
        try {
            // 对数据进行验证
            $this->validate();
            // 对特殊的模型进行处理，如设置 owner 等

            // 填充模型属性
            $this->fill();

            // 添加操作员信息
            $this->withOperator();

            // 存储模型到仓库
            $this->repository->store($this->model);

            $this->commitDatabaseTransaction();
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
        return $this->model;
    }

    protected function createModel(Data $command) : Model
    {
        if (method_exists(static::$modelClass, 'create')) {
            return static::$modelClass::create($command);
        }
        return new (static::$modelClass)();
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
