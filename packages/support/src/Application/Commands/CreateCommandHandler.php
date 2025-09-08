<?php

namespace RedJasmine\Support\Application\Commands;


use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class CreateCommandHandler extends BaseCommandHandler
{

    protected string $name = 'create';

    public function __construct($service)
    {
        $this->service = $service;
        $this->initHandleContext();
    }


    protected function validate(HandleContext $context) : void
    {
        // 业务逻辑验证
        if (method_exists($context->getCommand(), 'validateBusinessRules')) {

            $context->getCommand()->validateBusinessRules();
        }
    }

    /**
     * 获取模型
     *
     * @param  Data  $command
     *
     * @return Model
     */
    protected function getModel(Data $command) : Model
    {
        return $this->service->newModel($command);
    }

    protected function save(HandleContext $context) : void
    {
        $this->service->repository->store($this->context->getModel());
    }


    /**
     * 填充转换数据
     *
     * @param  HandleContext  $context
     *
     * @return void
     */
    protected function fill(HandleContext $context) : void
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


    }


}
