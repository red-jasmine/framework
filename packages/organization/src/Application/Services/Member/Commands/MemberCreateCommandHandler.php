<?php

namespace RedJasmine\Organization\Application\Services\Member\Commands;

use RedJasmine\Organization\Application\Services\Member\MemberApplicationService;
use RedJasmine\Organization\Domain\Models\Member;
use RedJasmine\Support\Application\Commands\BaseCommandHandler;
use RedJasmine\Support\Application\HandleContext;
use RedJasmine\Support\Data\Data;
use Illuminate\Database\Eloquent\Model;

class MemberCreateCommandHandler extends BaseCommandHandler
{
    protected string $name = 'create';

    public function __construct(protected MemberApplicationService $service)
    {
        $this->initHandleContext();
    }

    protected function getModel(Data $command) : Model
    {
        return Member::make();
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


