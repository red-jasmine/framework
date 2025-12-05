<?php

namespace RedJasmine\Support\Application\Commands;


use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class CreateCommandHandler extends CommonCommandHandler
{

    protected string $name = 'create';

    /**
     * 调用时注入的领域服务
     *
     * @param $service
     */
    public function __construct($service)
    {
        $this->service = $service;
    }

    protected function validate(CommandContext $context) : void
    {
        $command = $context->getCommand();
        if (method_exists($command, 'validateBusinessRules')) {
            $command->validateBusinessRules();
        }

    }


    protected function resolve(CommandContext $context) : Model
    {
        $command = $context->getCommand();
        // 判断是否设置 领域服务，通过领域服务创建
        return $this->service->newModel($command);
    }

    /**
     * 执行命令
     *
     * @param  CommandContext  $context
     *
     * @return void
     */
    protected function execute(CommandContext $context) : void
    {
        // 保持原有方式
        if (property_exists($this->service, 'transformer')) {
            if ($this->service->transformer instanceof TransformerInterface) {
                $context->setModel($this->service->transformer->transform($context->getCommand(), $context->getModel()));
            }
        } else {
            $context->getModel()->fill($context->getCommand()->all());
        }

        if ($context->getModel() instanceof OwnerInterface && property_exists($context->getCommand(), 'owner')) {
            $context->getModel()->owner = $context->getCommand()->owner;
        }

    }

    /**
     * 持久化数据
     *
     * @param  CommandContext  $context
     *
     * @return Model|null
     */
    protected function persist(CommandContext $context) : ?Model
    {
        if (isset($this->service->repository)) {
            return $this->service->repository->store($this->context->getModel());
        }

        // 直接调用 laravel model 的 方法
        $this->context->getModel()->push();
        return $this->context->getModel();

    }


}
