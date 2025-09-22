<?php

namespace RedJasmine\Organization\Application\Services\DepartmentManager\Commands;

use RedJasmine\Organization\Application\Services\DepartmentManager\DepartmentManagerApplicationService;
use RedJasmine\Support\Application\Commands\BaseCommandHandler;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\Support\Data\Data;
use Illuminate\Database\Eloquent\Model;

class DepartmentManagerUpdateCommandHandler extends BaseCommandHandler
{
    protected string $name = 'update';

    public function __construct(protected DepartmentManagerApplicationService $service)
    {
        $this->initHandleContext();
    }

    protected function getModel(Data $command) : Model
    {
        return $this->service->repository->find($command->getKey());
    }

    protected function validate(HandleContext $context) : void
    {
    }
    protected function fill(HandleContext $context) : void
    {
        $context->setModel($this->service->transformer->transform($context->getCommand(), $context->getModel()));
    }
    protected function save(HandleContext $context) : void
    {
        $this->service->repository->update($context->getModel());
    }
}


