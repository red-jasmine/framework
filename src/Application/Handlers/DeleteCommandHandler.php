<?php

namespace RedJasmine\Support\Application\Handlers;

use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Facades\ServiceContext;

class DeleteCommandHandler extends CommandHandler
{

    public function handle(Data $command) : void
    {

        $this->model = $this->getService()->getRepository()->find($command->id);

        if (method_exists($this->model, 'updater')) {
            $this->model->updater = ServiceContext::getOperator();
        }
        $this->execute(
            execute: null,
            persistence: fn() => $this->getService()->getRepository()->delete($this->model),
        );
    }
}
