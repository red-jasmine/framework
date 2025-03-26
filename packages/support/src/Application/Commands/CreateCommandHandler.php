<?php

namespace RedJasmine\Support\Application\Commands;


use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;
use Throwable;

class CreateCommandHandler extends CommandHandler
{

    public function __construct(protected ApplicationService $service)
    {
        $this->context = new HandleContext();
    }


    protected function newModel() : Model
    {
        return $this->service->newModel();
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


        $this->context->setCommand($command);
        $this->context->setModel($this->newModel());
        // 开始数据库事务
        $this->beginDatabaseTransaction();
        try {
            // 对数据进行验证
            $this->service->hook('create.validate', $this->context, fn() => $this->validate($this->context));

            $this->service->hook('create.fill', $this->context, fn() => $this->fill($this->context));

            // 存储模型到仓库
            $this->service->repository->store($this->context->model);

            // 提交事务
            $this->commitDatabaseTransaction();
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }

        return $this->context->model;
    }


    protected function validate(HandleContext $context) : void
    {

    }


    /**
     * 填充转换数据
     *
     * @param  HandleContext  $context
     *
     * @return Model
     */
    protected function fill(HandleContext $context) : Model
    {

        if (property_exists($this->service, 'transformer')) {
            if ($this->service->transformer instanceof TransformerInterface) {
                $context->setModel($this->service->transformer->transform($context->getCommand(), $context->getModel()));
            }
        } else {
            $context->getModel()->fill($context->getCommand()->all());
        }

        if ($context->model instanceof OwnerInterface && property_exists($context->getCommand(), 'owner')) {
            $context->model->owner = $context->getCommand()->owner;
        }
        return $context->getModel();

    }


}
