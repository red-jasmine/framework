<?php

namespace RedJasmine\Support\Application\CommandHandlers;


use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Application\ApplicationCommandService;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;
use Throwable;

class CreateCommandHandler extends CommandHandler
{

    public function __construct(protected ApplicationCommandService $service)
    {
    }


    /**
     * 处理命令对象
     *
     * @param  Data  $command  被处理的命令对象
     *
     * @return Model|null 返回处理后的模型对象或其他相关结果
     * @throws Throwable
     */
    public function handle(Data $command) : ?Model
    {
        // TODO
        // 创建模型
        // 构建处理上下文
        $handleContext = new HandleContext();
        $handleContext->setCommand($command);

        // 开启事务
        // 验证命令
        // 填充模型
        // 存储模型到仓库
        // 提交事务

        // 设置命令对象
        $this->setCommand($command);

        // 创建领域模型
        $this->setModel($this->createModel($command));

        // 开始数据库事务
        $this->beginDatabaseTransaction();
        try {
            // 对数据进行验证
            $this->service->hook('create.validate', $command, fn() => $this->validate($command));

            $this->service->hook('create.fill', $command, fn() => $this->fill($command));

            // 存储模型到仓库
            $this->service->repository->store($this->model);

            // 提交事务
            $this->commitDatabaseTransaction();
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }

        return $this->model;
    }

    /**
     * @param  Data  $command
     *
     * @return Model
     * @throws \Exception
     * @deprecated
     */
    protected function createModel(Data $command) : Model
    {

        if ($this->getService()) {
            return $this->getService()->newModel($command);
        }
        if (method_exists($this->service, 'newModel')) {
            // TODO 弃用
            return $this->service->newModel($command);
        }

        return $this->service::getModelClass()::make();
    }


    protected function validate(Data $command) : void
    {

    }


    /**
     * 填充转换数据
     *
     * @param  Data  $command
     *
     * @return Model
     */
    protected function fill(Data $command) : Model
    {


        if (property_exists($this->service, 'transformer')) {
            if ($this->service->transformer instanceof TransformerInterface) {
                $this->model = $this->service->transformer->transform($command, $this->model);
            }
        } else {

            $this->model->fill($command->all());
        }

        if ($this->model instanceof OwnerInterface && property_exists($command, 'owner')) {
            $this->model->owner = $command->owner;
        }
        return $this->model;

    }


}
