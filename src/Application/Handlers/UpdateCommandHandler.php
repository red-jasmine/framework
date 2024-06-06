<?php

namespace RedJasmine\Support\Application\Handlers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Facades\ServiceContext;

class UpdateCommandHandler extends CommandHandler
{

    /**
     * @param Data $command
     *
     * @return void
     */
    public function handle(Data $command) : void
    {

        $model           = $this->getService()->getRepository()->find($command->id);
        $this->model = $model;
        $model->fill($command->toArray());
        if (method_exists($model, 'updater')) {
            $model->updater =ServiceContext::getOperator();
        }
        $execute = method_exists($model, 'modify') ? fn() => $model->modify() : null;
        $this->execute(
            execute: $execute,
            persistence: fn() => $this->getService()->getRepository()->update($model),
        );
    }
}
