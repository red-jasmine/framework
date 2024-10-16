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
     * @param Data $command 被处理的命令对象
     *
     * @return Model|null 返回处理后的模型对象或其他相关结果
     * @throws Throwable
     */
    public function handle(Data $command) : ?Model
    {

        // 设置命令对象
        $this->setCommand($command);

        // 创建领域模型
        $this->setModel($this->createModel($command));

        // 开始数据库事务
        $this->beginDatabaseTransaction();
        try {
            // 对数据进行验证
            $this->hook('create.validate', $command, fn() => $this->validate($command));

            $this->hook('create.fill', $command, fn() => $this->fill($command));

            // 存储模型到仓库
            $this->getRepository()->store($this->model);

            // 提交事务
            $this->commitDatabaseTransaction();
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }

        return $this->model;
    }

    protected function createModel(Data $command) : Model
    {
        if ($this->getService()) {
            return $this->getService()->newModel($command);
        }

        if (method_exists($this->getModelClass(), 'create')) {
            return $this->getModelClass()::create($command);
        }
        return new ($this->getModelClass())();
    }


    protected function validate(Data $command) : void
    {

    }


    protected function fill(Data $command) : void
    {

        $this->model->fill($command->all());

        if ($this->model instanceof OwnerInterface && property_exists($command, 'owner')) {
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
