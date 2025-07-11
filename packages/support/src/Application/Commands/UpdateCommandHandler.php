<?php

namespace RedJasmine\Support\Application\Commands;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Application\CommandHandlers\Throwable;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

class UpdateCommandHandler extends RestCommandHandler
{

    public function __construct(protected $service)
    {
        $this->initHandleContext();
    }

    protected string $name = 'update';

    protected function getModel(Data $command) : Model
    {
        return $this->service->repository->find($command->getKey());
    }

    protected function save(HandleContext $context) : void
    {
        $this->service->repository->update($this->context->getModel());
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
