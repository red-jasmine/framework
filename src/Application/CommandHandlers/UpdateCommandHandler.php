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
    protected static string $modelClass;
    protected Model|null    $model = null;
    /**
     * @var mixed
     */
    protected Data|null $command;

    public function __construct(
        protected RepositoryInterface $repository
    ) {
    }

    /**
     * @return \Model|null
     */
    public function getModel() : ?\Model
    {
        return $this->model;
    }

    /**
     * @param  Model  $model
     *
     * @return static
     */
    public function setModel(Model $model) : static
    {
        $this->model = $model;
        return $this;
    }

    public function handle(Data $command) : ?Model
    {
        $this->setModel($this->repository->find($command->id));


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

    public function setCommand($command) : static
    {
        $this->command = $command;
        return $this;
    }


}
