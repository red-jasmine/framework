<?php

namespace RedJasmine\Support\Application\Commands;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Application\CommandHandlers\Throwable;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\Support\Data\Data;

/**
 * 删除命令处理器类
 * 继承自CommandHandler，提供删除数据的处理逻辑
 */
class DeleteCommandHandler extends RestCommandHandler
{


    protected string $name = 'delete';

    public function __construct(
        protected $service
    ) {
        $this->getContext();
    }

    protected function getModel(Data $command) : Model
    {
        return $this->service->repository->find($command->getKey());
    }

    protected function validate(HandleContext $context) : void
    {
        return;
    }

    protected function fill(HandleContext $context) : void
    {

    }

    protected function save(HandleContext $context) : void
    {
        $this->service->repository->delete($this->context->getModel());
    }


}
