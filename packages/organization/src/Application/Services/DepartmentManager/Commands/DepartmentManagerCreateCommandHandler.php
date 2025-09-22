<?php

namespace RedJasmine\Organization\Application\Services\DepartmentManager\Commands;

use RedJasmine\Organization\Application\Services\DepartmentManager\DepartmentManagerApplicationService;
use RedJasmine\Organization\Domain\Models\DepartmentManager;
use RedJasmine\Support\Application\Commands\BaseCommandHandler;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\Support\Data\Data;
use Illuminate\Database\Eloquent\Model;

class DepartmentManagerCreateCommandHandler extends BaseCommandHandler
{
    protected string $name = 'create';

    public function __construct(protected DepartmentManagerApplicationService $service)
    {
        $this->initHandleContext();
    }

    protected function getModel(Data $command) : Model
    {
        return DepartmentManager::make();
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
        $this->service->repository->store($context->getModel());
    }
}


