<?php

namespace RedJasmine\Support\Application\Handlers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Application\CommandHandler;
use RedJasmine\Support\Data\Data;

class UpdateCommandHandler extends CommandHandler
{

    public function handle(Data $command) : void
    {

        $model           = $this->getService()->getRepository()->find($command->id);
        $this->aggregate = $model;
        $model->fill($command->toArray());
        if (method_exists($model, 'updater')) {
            $model->updater = $this->getOperator();
        }
        $execute = method_exists($model, 'modify') ? fn() => $model->modify() : null;
        $this->execute(
            execute: $execute,
            persistence: fn() => $this->getService()->getRepository()->update($model),
        );
    }
}
