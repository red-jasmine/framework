<?php

namespace RedJasmine\Support\Application\Commands;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Application\CommandHandlers\Throwable;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class UpdateCommandHandler extends CommandHandler
{

    public function __construct(protected ApplicationService $service)
    {
        $this->context = new HandleContext();
    }

    public function handle(Data $command) : ?Model
    {

        $this->context->setCommand($command);

        $this->context->setModel($this->service->repository->find($command->getKey()));
        // 开始数据库事务
        $this->beginDatabaseTransaction();
        try {

            // 对数据进行验证
            $this->service->hook('update.validate', $this->context, fn() => $this->validate($this->context));
            // 填充模型属性
            $this->service->hook('update.fill', $this->context, fn() => $this->fill($this->context));

            // 存储模型到仓库
            $this->service->repository->update($this->context->model);

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

    protected function fill(HandleContext $context) : void
    {

        if (property_exists($this->service, 'transformer')) {
            if ($this->service->transformer instanceof TransformerInterface) {
                $context->setModel($this->service->transformer->transform($context->getCommand(), $context->getModel()));
            }
        } else {
            $context->getModel()->fill($context->getCommand()->all());
        }
    }


}
