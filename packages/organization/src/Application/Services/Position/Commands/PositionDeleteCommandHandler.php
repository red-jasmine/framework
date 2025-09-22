<?php

namespace RedJasmine\Organization\Application\Services\Position\Commands;

use RedJasmine\Organization\Application\Services\Position\PositionApplicationService;
use RedJasmine\Support\Application\Commands\BaseCommandHandler;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\Support\Data\Data;
use Illuminate\Database\Eloquent\Model;

class PositionDeleteCommandHandler extends BaseCommandHandler
{
    protected string $name = 'delete';

    public function __construct(protected PositionApplicationService $service)
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
    }
    protected function save(HandleContext $context) : void
    {
        $this->service->repository->delete($context->getModel());
    }
}


