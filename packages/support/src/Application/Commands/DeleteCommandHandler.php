<?php

namespace RedJasmine\Support\Application\Commands;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Application\CommandHandlers\Throwable;

/**
 * 删除命令处理器类
 * 继承自CommandHandler，提供删除数据的处理逻辑
 */
class DeleteCommandHandler extends CommonCommandHandler
{


    protected string $name = 'delete';

    public function __construct($service)
    {
        $this->service = $service;
    }

    protected function validate(CommandContext $context) : void
    {
        // TODO: Implement validate() method.
    }


    protected function resolve(CommandContext $context) : Model
    {
        $command = $context->getCommand();
        if (isset($this->service->repository)) {
            return $this->service->repository->find($command->getKey()); // TODO key?
        }
        return $this->getModelClass()::findOrFail($command->getKey());
    }

    protected function execute(CommandContext $context)
    {
        // TODO: Implement execute() method.
    }

    protected function persist(CommandContext $context) : ?bool
    {
        if (isset($this->service->repository)) {
            return $this->service->repository->delete($this->context->getModel());
        }
        return $this->context->getModel()->delete();
    }


}
