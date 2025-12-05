<?php

namespace RedJasmine\Support\Application\Commands;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Application\CommandHandlers\Throwable;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class UpdateCommandHandler extends CommonCommandHandler
{
    protected string $name = 'update';

    public function __construct($service)
    {
        $this->service = $service;
    }

    protected function validate(CommandContext $context) : void
    {

        // 业务逻辑验证
        if (method_exists($context->getCommand(), 'validateBusinessRules')) {
            $context->getCommand()->validateBusinessRules();
        }
    }

    protected function resolve(CommandContext $context) : Model
    {
        $command = $context->getCommand();
        if (isset($this->service->repository)) {
            return $this->service->repository->find($command->getKey()); // TODO key?
        }
        return $this->getModelClass()::findOrFail($command->getKey());
    }

    protected function execute(CommandContext $context) : void
    {
        // TODO 后续这里的处理 应该丢给 领域服务
        if (property_exists($this->service, 'transformer')) {
            if ($this->service->transformer instanceof TransformerInterface) {
                $context->setModel($this->service->transformer->transform($context->getCommand(), $context->getModel()));
            }
        } else {
            // 没有设置领域服务  走降级处理
            $context->getModel()->fill($context->getCommand()->all());
        }
    }

    /**
     * 持久化
     *
     * @param  CommandContext  $context
     *
     * @return Model|null
     */
    protected function persist(CommandContext $context) : ?Model
    {
        if (isset($this->service->repository)) {
            return $this->service->repository->update($this->context->getModel());

        }
        $this->context->getModel()->push();
        return $this->context->getModel();
    }


}
